<?php
namespace controller;

use controller\error\ErrorMessages;
use controller\error\ErrorMessage;
use infra\Route;
use model\exception\http\BadRequestException;
use model\utils\StringUtils;
use service\UserService;
use Throwable;

class AuthenticateController extends Controller {
    private static ?AuthenticateController $_instance = null;

    /**
     * AuthenticateController constructor.
     */
    private function __construct() {
        parent::__construct("/auth");
    }

    /**
     * @return AuthenticateController|null
     */
    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new AuthenticateController();
        }

        return self::$_instance;
    }


    /**
     * @return mixed|void
     */
    function initRoutes() {
        Route::add($this->makeRoute('/login'), 'post', array($this, 'login'));
        Route::add($this->makeRoute('/logout'), 'post', array($this, 'logout'));
    }

    /**
     *
     */
    public function login() {
        try {
            if($this->hasSessionUser()) {
                throw new BadRequestException("User already logged.");
            }

            $this->validateLogin();

            $user = UserService::getInstance()->findByEmail($this->getPOSTParam('email'));

            if(!password_verify($this->getPOSTParam('password'), $user->getPassword())) {
                $errors = new ErrorMessages();
                $errors->addError(new ErrorMessage('email', "L'email et le mot de passe ne match pas."));
                $errors->addError(new ErrorMessage('password', ""));
                throw new BadRequestException(strval($errors));
            }

            $this->setSessionUser($user);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     *
     */
    public function logout() {
        try {
            if(!$this->hasSessionUser()) {
                throw new BadRequestException("User is not logged.");
            }

            // remove all session variables
            session_unset();
            // destroy the session
            session_destroy();
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }


    /**
     * @throws BadRequestException
     */
    private function validateLogin() {
        $errors = new ErrorMessages();

        if(StringUtils::isEmpty($this->getPOSTParam('email'))) {
            $errors->addError(new ErrorMessage('email', "L'email est requis."));
        }

        if(StringUtils::isEmpty($this->getPOSTParam('password'))) {
            $errors->addError(new ErrorMessage('password', 'Le mot de passe est requis.'));
        }

        if(!$errors->isEmpty()) {
            throw new BadRequestException(strval($errors));
        }
    }
}
