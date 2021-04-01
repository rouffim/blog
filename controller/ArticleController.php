<?php
namespace controller;

use controller\error\ErrorMessages;
use controller\error\ErrorMessage;
use infra\Route;
use model\article\Article;
use model\exception\http\BadRequestException;
use model\exception\http\InternalErrorException;
use model\user\Permission;
use model\utils\StringUtils;
use model\utils\UuidUtils;
use service\ArticleService;
use Throwable;

class ArticleController extends Controller {
    private static ?ArticleController $_instance = null;

    /**
     * ArticleController constructor.
     */
    private function __construct() {
        parent::__construct("/article");
    }

    /**
     * @return ArticleController|null
     */
    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new ArticleController();
        }

        return self::$_instance;
    }


    /**
     * @return mixed|void
     */
    function initRoutes() {
        Route::add($this->makeRoute(), 'get', array($this, 'findArticle'));
        Route::add($this->makeRoute('/all'), 'get', array($this, 'findArticles'));
        Route::add($this->makeRoute(), 'post', array($this, 'saveArticle'));
        Route::add($this->makeRoute(), 'delete', array($this, 'deleteArticle'));
    }

    /**
     *
     */
    public function findArticle() {
        try {
            $article = ArticleService::getInstance()
                ->find($this->getGETParam('uuid'));

            echo $this->modelToJson($article);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     *
     */
    public function findArticles() {
        try {
            $gd = null;
            $articles = ArticleService::getInstance()
                ->findAll($this->getGETParam('search'), $this->getRequestPageable());

            echo $this->modelsToJson($articles);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     *
     */
    public function saveArticle() {
        try {
            $this->validateArticle();

            $update = false;
            $article = null;
            $uuid = $this->getPOSTParam('uuid');

            if(!StringUtils::isEmpty($uuid)) {
                $update = true;
                $article = ArticleService::getInstance()->find($uuid);

                if(is_null($article)) {
                    throw new InternalErrorException('Try to update a non-existent article.');
                }

                $this->checkSessionUserPermission(
                    $article->getUser()->getUuid() == $this->getSessionUser()->getUuid() ?
                        Permission::$EDIT_OWN_ARTICLE :
                        Permission::$EDIT_ALL_ARTICLE);
                $this->checkVersion($article->getVersion());
            } else {
                $this->checkSessionUserPermission(Permission::$ADD_ARTICLE);
                $article = new Article();
            }

            $article->setExcerpt($this->getPOSTParam('excerpt'));
            $article->setTitle($this->getPOSTParam('title'));
            $article->setBody($this->getPOSTParam('body'));
            $article->setUserId($this->getSessionUserId());
            $article->setImageExtension(
    $this->requestHasFile('image') ?
                        $this->saveFile($article,"image") :
                        $this->getPOSTParam('image_extension'));

            ArticleService::getInstance()->save($article, $update);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     *
     */
    public function deleteArticle() {
        try {
            $uuid = $this->getGETParam('uuid');
            $article = ArticleService::getInstance()->find($uuid);

            if(is_null($article)) {
                throw new BadRequestException("The given article not exists.");
            }

            $this->checkSessionUserPermission(
                $article->getUser()->getUuid() == $this->getSessionUser()->getUuid() ?
                    Permission::$REMOVE_OWN_ARTICLE :
                    Permission::$REMOVE_ALL_ARTICLE);

            ArticleService::getInstance()->delete($uuid);
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }


    /**
     * @throws BadRequestException
     */
    private function validateArticle() {
        $errors = new ErrorMessages();

        if(StringUtils::isEmpty($this->getPOSTParam('title'))) {
            $errors->addError(new ErrorMessage('title', 'Le titre est requis.'));
        }

        if(StringUtils::isEmpty($this->getPOSTParam('body'))) {
            $errors->addError(new ErrorMessage('body', 'Le contenu est requis.'));
        }

        // Should not happen, user can't deal with it
        if(!StringUtils::isEmpty($this->getPOSTParam('uuid') && !UuidUtils::isValidUuid($this->getPOSTParam('uuid')))) {
            $errors->addError(new ErrorMessage('uuid', 'Uuid invalid.'));
        }

        // Check image file
        $this->validateImage($errors, 'image');

        $this->validateUuid($errors);

        //TODO check image_extension or retrieve it from files

        if(!$errors->isEmpty()) {
            throw new BadRequestException(strval($errors));
        }
    }
}
