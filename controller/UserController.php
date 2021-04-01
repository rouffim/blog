<?php
namespace controller;

use controller\error\ErrorMessages;
use controller\error\ErrorMessage;
use infra\Route;
use model\exception\http\UnauthorizedException;
use model\user\Permission;
use model\user\Role;
use model\user\User;
use model\exception\http\BadRequestException;
use model\exception\http\InternalErrorException;
use model\utils\StringUtils;
use service\UserService;
use Throwable;

class UserController extends Controller {
    private static ?UserController $_instance = null;

    /**
     * UserController constructor.
     */
    private function __construct() {
        parent::__construct("/user");
    }

    /**
     * @return UserController|null
     */
    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new UserController();
        }

        return self::$_instance;
    }


    /**
     * @return mixed|void
     */
    function initRoutes() {
        Route::add($this->makeRoute(), 'get', array($this, 'findUser'));
        Route::add($this->makeRoute('/all'), 'get', array($this, 'findUsers'));
        Route::add($this->makeRoute(), 'post', array($this, 'saveUser'));
        Route::add($this->makeRoute('/role'), 'post', array($this, 'changeUserRole'));
        Route::add($this->makeRoute(), 'delete', array($this, 'deleteUser'));
    }

    /**
     *
     */
    public function findUser() {
        try {
            $user = UserService::getInstance()
                ->find($this->getGETParam('uuid'));

            echo $this->modelToJson($user);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     *
     */
    public function findUsers() {
        try {
            $users = UserService::getInstance()
                ->findAll($this->getGETParam('search'), $this->getRequestPageable());

            echo $this->modelsToJson($users);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     *
     */
    public function saveUser() {
        try {
            $this->validateUser();

            $update = false;
            $user = null;
            $uuid = $this->getPOSTParam('uuid');

            if(!StringUtils::isEmpty($uuid)) {
                $update = true;
                $user = UserService::getInstance()->find($uuid);

                if(is_null($user)) {
                    throw new InternalErrorException('Try to update a non-existent user.');
                }

                if($user->getUuid() != $this->getSessionUser()->getUuid()) {
                    throw new UnauthorizedException("Un autre utilisateur essaye de modifier un utilisateur.");
                }

                $this->checkVersion($user->getVersion());
            } else {
                if($this->hasSessionUser()) {
                    throw new BadRequestException("Utilisateur déjà loggué.");
                }

                $user = new User();
                $user->setEmail($this->getPOSTParam('email'));
                $user->setPseudo($this->getPOSTParam('pseudo'));
                $user->setPassword(StringUtils::encryptPassword($this->getPOSTParam('password')));
                $user->setRoleId(Role::$VIEWER);
            }

            $user->setImageExtension(
                $this->requestHasFile('image') ?
                    $this->saveFile($user,"image") :
                    $this->getPOSTParam('image_extension'));

            UserService::getInstance()->save($user, $update);

            $this->setSessionUser($user);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     *
     */
    public function changeUserRole() {
        try {
            $this->validateChangeUserRole();

            $roleId = intval($this->getPOSTParam('role'));

            if(!$this->hasSessionUser() || $this->getSessionUser()->getRoleId() < $roleId || !$this->checkSessionUserPermission(Permission::$CHANGE_ROLE)) {
                throw new UnauthorizedException("L'utilisateur n'a pas le droit d'attribuer ce role.");
            }

            $uuid = $this->getPOSTParam('uuid');
            $user = UserService::getInstance()->find($uuid);

            if(is_null($user)) {
                throw new BadRequestException('Try to update a non-existent user.');
            }
            if($this->getSessionUser()->getUuid() == $user->getUuid()) {
                throw new UnauthorizedException("L'utilisateur n'a pas le droit de modifier son role.");
            }

            $user->setRoleId($roleId);

            UserService::getInstance()->save($user, true);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     *
     */
    public function deleteUser() {
        try {
            UserService::getInstance()
                ->delete($this->getGETParam('uuid'));
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }


    /**
     * @throws BadRequestException
     */
    private function validateUser() {
        $errors = new ErrorMessages();

        $uuid = $this->getPOSTParam('uuid');
        $this->validateUuid($errors);

        if(StringUtils::isEmpty($uuid)) {
            $email = $this->getPOSTParam('email');
            if(StringUtils::isEmpty($email)) {
                $errors->addError(new ErrorMessage('email', "L'email est requis."));
            } else if(!StringUtils::isEmail($email)) {
                $errors->addError(new ErrorMessage('email', "L'email est invalide."));
            } else if(!StringUtils::isEmpty($uuid) && !is_null(UserService::getInstance()->findByEmail($email))) {
                $errors->addError(new ErrorMessage('email', "L'email est déjà utilisé."));
            }

            $pseudo = $this->getPOSTParam('pseudo');
            if(StringUtils::isEmpty($pseudo)) {
                $errors->addError(new ErrorMessage('pseudo', "Le pseudo est requis."));
            } else if(!StringUtils::isEmpty($uuid) && !is_null(UserService::getInstance()->findByPseudo($pseudo))) {
                $errors->addError(new ErrorMessage('email', "Le pseudo est déjà utilisé."));
            }

            $password = $this->getPOSTParam('password');
            $password_confirm = $this->getPOSTParam('password_confirm');
            if (StringUtils::isEmpty($password)) {
                $errors->addError(new ErrorMessage('password', 'Le mot de passe est requis.'));
                $errors->addError(new ErrorMessage('password_confirm', ''));
            } else if (strlen($password) < 6) {
                $errors->addError(new ErrorMessage('password', 'Le mot de passe doit faire au moins 6 caractères.'));
                $errors->addError(new ErrorMessage('password_confirm', ''));
            } else if (strlen($password) > 250) {
                $errors->addError(new ErrorMessage('password', 'Le mot de passe doit faire au maximum 250 caractères.'));
                $errors->addError(new ErrorMessage('password_confirm', ''));
            } else if ($password != $password_confirm) {
                $errors->addError(new ErrorMessage('password', 'Les deux mots de passes ne correspondent pas.'));
                $errors->addError(new ErrorMessage('password_confirm', ''));
            }
        }

        // Check image file
        $this->validateImage($errors, 'image');

        //TODO check image_extension or retrieve it from files

        if(!$errors->isEmpty()) {
            throw new BadRequestException(strval($errors));
        }
    }

    /**
     * @throws BadRequestException
     */
    private function validateChangeUserRole() {
        $errors = new ErrorMessages();
        $roleId = $this->getPOSTParam('role');

        if(StringUtils::isEmpty($roleId)) {
            $errors->addError(new ErrorMessage('role', 'Le role est requis.'));
        } else if(!is_numeric($roleId) || !Role::isValid(intval($roleId))) {
            $errors->addError(new ErrorMessage('role', 'Le role est invalide.'));
        }

        $this->validateUuid($errors);

        if(!$errors->isEmpty()) {
            throw new BadRequestException(strval($errors));
        }
    }
}
