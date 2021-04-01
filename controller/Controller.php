<?php
namespace controller;

use controller\error\ErrorMessage;
use controller\error\ErrorMessages;
use DateTime;
use InvalidArgumentException;
use model\AbstractModel;
use model\exception\http\ConflictException;
use Exception;
use model\exception\http\ForbiddenException;
use model\exception\http\InternalErrorException;
use model\exception\http\UnauthorizedException;
use model\Pageable;
use model\user\User;
use model\utils\FileUtils;
use model\utils\UuidUtils;
use model\utils\StringUtils;
use service\UserService;
use Throwable;

abstract class Controller {
    protected string $baseRoute;


    /**
     * Controller constructor.
     * @param string $baseRoute
     */
    protected function __construct(string $baseRoute) {
        $this->baseRoute = '/api' . $baseRoute;

        $this->initRoutes();
    }


    /**
     * @return mixed
     */
    public abstract function initRoutes();

    /**
     * @return string
     */
    public function getBaseRoute(): string {
        return $this->baseRoute;
    }

    /**
     * @param string $route
     * @return string
     */
    public function makeRoute(string $route = ''): string {
        return $this->baseRoute . $route;
    }

    /**
     * @param string $param
     * @return string|null
     */
    protected function getGETParam(string $param): ?string {
        return isset($_GET[$param]) ? $_GET[$param] : null;
    }

    /**
     * @param string $param
     * @return string|null
     */
    protected function getPOSTParam(string $param): ?string {
        return isset($_POST[$param]) ? $_POST[$param] : null;
    }

    /**
     * @param int $permission
     * @param bool $throwException
     * @return bool
     * @throws ForbiddenException
     * @throws UnauthorizedException
     * @throws Exception
     */
    protected function checkSessionUserPermission(int $permission, bool $throwException = true): bool {
        $user = $this->getSessionUser();

        $ok = !is_null($user) && $user->getRole()->hasPermission($permission);

        if($throwException && !$ok) {
            throw is_null($user) ? new UnauthorizedException() : new ForbiddenException();
        }

        return $ok;
    }

    /**
     * @param int $role
     * @param bool $throwException
     * @return bool
     * @throws ForbiddenException
     * @throws UnauthorizedException
     */
    protected function checkSessionUserRole(int $role, bool $throwException = true): bool {
        $user = $this->getSessionUser();
        $ok = !is_null($user) && $user->getRoleId() >= $role;

        if($throwException && !$ok) {
            throw is_null($user) ? new UnauthorizedException() : new ForbiddenException();
        }

        return $ok;
    }

    /**
     * @return bool
     */
    protected function hasSessionUser(): bool {
        return isset($_SESSION["user"]);
    }

    /**
     * @return int|null
     * @throws Exception
     */
    protected function getSessionUserId(): ?int {
        if($this->hasSessionUser()) {
            $userSession = $this->getSessionUser();
            $user = UserService::getInstance()->find($userSession->getUuid());
            if(!is_null($user)) {
                return $user->getId();
            }
        }

        return null;
    }

    /**
     * @return User|null
     */
    protected function getSessionUser(): ?User {
        $user = null;

        if($this->hasSessionUser()) {
            $user = new User();

            $user->setUuid($_SESSION["user"]["uuid"]);
            $user->setPseudo($_SESSION["user"]["pseudo"]);
            $user->setEmail($_SESSION["user"]["email"]);
            $user->setImageExtension($_SESSION["user"]["image_extension"]);
            $user->setRoleId($_SESSION["user"]["id_role"]);
        }

        return $user;
    }

    /**
     * @param User $user
     * @return array
     * @throws Exception
     */
    protected function setSessionUser(User $user) {
        if(is_null($user)) {
            throw new InvalidArgumentException('User must not be null.');
        }
        return $_SESSION["user"] = $user->toMap(true);
    }

    /**
     * @param Throwable $exception
     */
    protected function handleException(Throwable $exception) {
        $code = is_subclass_of($exception, 'model\exception\http\HttpException') ? $exception->getCode() : 500;

        if($code == 500) {
            //TODO in production, users must not see HTTP 500 exception
            echo $exception;
        } else {
            echo $exception->getMessage();
        }

        http_response_code($code);
        die();
    }

    /**
     * @param $currentVersion
     * @throws ConflictException
     */
    protected function checkVersion($currentVersion) {
        $version = $this->getPOSTParam('version');
        $versionDateTime = DateTime::createFromFormat(DATE_ISO8601, $version);

        if(empty($version) || !$versionDateTime) {
            throw new ConflictException('Invalid form version');
        }

        if($currentVersion > $versionDateTime) {
            throw new ConflictException('Current version is newer than current one.');
        }
    }

    /**
     * @return Pageable
     */
    protected function getRequestPageable(): Pageable {
        $pageable =  new Pageable();
        $pageable->setIndexFromString($this->getGETParam('index'));
        $pageable->setOffsetFromString($this->getGETParam('offset'));
        $pageable->setSortKey($this->getGETParam('sortKey'));
        $pageable->setSortType($this->getGETParam('sortType'));

        return $pageable;
    }

    /**
     * @param $model
     * @return string|null
     */
    protected function modelToJson($model): ?string {
        if(method_exists($model,'toMap')) {
            return json_encode($model->toMap(true));
        }

        return null;
    }

    /**
     * @param array $models
     * @return string
     */
    protected function modelsToJson(array $models): string {
        $mappedModels = array();

        foreach($models as $model) {
            if(method_exists($model,'toMap')) {
                array_push($mappedModels, $model->toMap(true));
            }
        }

        return json_encode($mappedModels);
    }

    /**
     * @param string $filename
     * @return bool
     */
    protected function requestHasFile(string $filename): bool {
        return isset($_FILES[$filename]) && !empty($_FILES[$filename]["name"]);
    }

    /**
     * @param ErrorMessages $errors
     * @param string $filename
     * @param int $maxFileSize
     * @param array|string[] $acceptedFileExtensions
     */
    protected function validateImage(ErrorMessages $errors, string $filename, int $maxFileSize = 5000000, array $acceptedFileExtensions = ['png', 'jpg', 'jpeg', 'gif']) {
        if($this->requestHasFile($filename)) {
            // 5000000 = Larger than 5MB
            if ($_FILES[$filename]["size"] > $maxFileSize) {
                $sizeToMB = $maxFileSize / 1000000;
                $errors->addError(new ErrorMessage('image', "L'image doit faire maximum " . $sizeToMB . "MB"));
            }

            if (empty($_FILES[$filename]["size"])) {
                $errors->addError(new ErrorMessage('image', "L'image n'est pas supportée."));
            }

            $imageFileType = FileUtils::getFileExtension($_FILES[$filename]["name"]);
            // Allow certain file formats
            if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                $acceptedExtensionsString = implode(', ', $acceptedFileExtensions);
                $errors->addError(new ErrorMessage('image', "L'image doit être de type $acceptedExtensionsString."));
            }
        }
    }

    /**
     * @param ErrorMessages $errors
     */
    protected function validateUuid(ErrorMessages $errors) {
        // Should not happen, user can't deal with it
        if(!StringUtils::isEmpty($this->getPOSTParam('uuid') && !UuidUtils::isValidUuid($this->getPOSTParam('uuid')))) {
            $errors->addError(new ErrorMessage('uuid', 'Uuid invalid.'));
        }
    }

    /**
     * @param AbstractModel $model
     * @param string $filename
     * @throws InternalErrorException
     * @throws Exception
     * @return string fileExtension
     */
    protected function saveFile(AbstractModel $model, string $filename): string
    {
        if(file_exists($filename)) {
            unlink($filename);
        }

        $fileExtension = FileUtils::getFileExtension($_FILES[$filename]["name"]);

        if (!move_uploaded_file($_FILES[$filename]["tmp_name"], FileUtils::getModelImagePath($model, $fileExtension))) {
            throw new InternalErrorException('Save image failed.');
        }

        return $fileExtension;
    }
}
