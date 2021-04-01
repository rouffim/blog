<?php

namespace service;

use Exception;
use InvalidArgumentException;
use model\AbstractModel;
use model\article\Article;
use model\exception\NotFoundException;
use model\Pageable;
use model\utils\UuidUtils;
use PDO;

class ArticleService extends ResourceService {
    private static ?ArticleService $_instance = null;
    private string $SELECT_QUERY = "SELECT id, uuid, version, title, excerpt, body, image_extension, id_user FROM article ";

    /**
     * ArticleService constructor.
     */
    private function __construct() {
        parent::__construct();
    }

    /**
     * @return ArticleService|null
     */
    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new ArticleService();
        }

        return self::$_instance;
    }


    /**
     * @param string $uuid
     * @return Article
     * @throws Exception
     */
    public function find(string $uuid): Article {
        if(!UuidUtils::isValidUuid($uuid)) {
            throw new InvalidArgumentException('Uuid is not valid');
        }

        try {
            $this->connectToDb();
            $stmt = $this->db->prepare("$this->SELECT_QUERY where uuid = :uuid LIMIT 1");
            $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();

            if ($result) {
                $article = $this->resultToArticle($result);

                $this->disconnectFromDb();
            } else {
                $this->disconnectFromDb();
                throw new NotFoundException();
            }

            return $article;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param string|null $search
     * @param Pageable|null $pageable
     * @return array
     * @throws Exception
     */
    public function findAll(?string $search = null, Pageable $pageable = null): array {
        try {
            $articles = array();
            $this->connectToDb();

            $sql = $this->SELECT_QUERY;
            $sql = $this->searchToSql($sql, 'title', $search);
            $sql = $this->pageableToSql($sql, $pageable);

            $stmt = $this->db->prepare($sql);
            $this->bindSearchParam($stmt, $search);
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($results) {
                foreach ($results as $row) {
                    array_push($articles, $this->resultToArticle($row));
                }
            }

            $this->disconnectFromDb();

            return  $articles;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param AbstractModel $article
     * @param bool $update
     * @return mixed|void
     * @throws Exception
     */
    public function save(AbstractModel $article, bool $update = false) {
        if(is_null($article)) {
            throw new InvalidArgumentException('article is null');
        }
        if(!($article instanceof Article)) {
            throw new InvalidArgumentException('article is not an Article');
        }

        try {
            $this->connectToDb();
            $this->db->beginTransaction();

            if($update) {
                $this->performUpdate($article);
            } else {
                $this->performSave($article);
            }

            $this->db->commit();
        } catch (Exception $exception) {
            $this->db->rollBack();

            throw $exception;
        } finally {
            $this->disconnectFromDb();
        }
    }

    /**
     * @param string $uuid
     * @return mixed|void
     * @throws Exception
     */
    public function delete(string $uuid) {
        if(!UuidUtils::isValidUuid($uuid)) {
            throw new InvalidArgumentException('Uuid is not valid');
        }

        try {
            $this->connectToDb();
            $stmt = $this->db->prepare("delete FROM article where uuid = :uuid");
            $stmt->bindParam(':uuid', $uuid, PDO::PARAM_STR);

            if(!$stmt->execute()) {
                $this->disconnectFromDb();
                throw new Exception();
            }

            $this->disconnectFromDb();
        } catch (Exception $exception) {
            throw $exception;
        }
    }


    /**
     * @param Article $article
     * @throws Exception
     */
    private function performSave(Article $article) {
        $properties = $article->toMap();
        $stmt = $this->db->prepare('INSERT INTO article(uuid, title, excerpt, body, image_extension, id_user) VALUES (:uuid, :title, :excerpt, :body, :image_extension, :id_user)');

        $stmt->bindParam(':uuid', $properties['uuid'], PDO::PARAM_STR);
        $stmt->bindParam(':excerpt', $properties['excerpt'], PDO::PARAM_STR);
        $stmt->bindParam(':title', $properties['title'], PDO::PARAM_STR);
        $stmt->bindParam(':body', $properties['body'], PDO::PARAM_STR);
        $stmt->bindParam(':image_extension', $properties['image_extension'], PDO::PARAM_STR);
        $stmt->bindParam(':id_user', $properties['id_user'], PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * @param Article $article
     * @throws Exception
     */
    private function performUpdate(Article $article) {
        $properties = $article->toMap();
        $stmt = $this->db->prepare('UPDATE article SET title = :title, excerpt = :excerpt, body = :body, image_extension = :image_extension, nb_views = :nbViews, is_pinned = :isPinned where uuid = :uuid');

        $stmt->bindParam(':title', $properties['title'], PDO::PARAM_STR);
        $stmt->bindParam(':excerpt', $properties['excerpt'], PDO::PARAM_STR);
        $stmt->bindParam(':body', $properties['body'], PDO::PARAM_STR);
        $stmt->bindParam(':image_extension', $properties['image_extension'], PDO::PARAM_STR);
        $stmt->bindParam(':nbViews', $properties['nbViews'], PDO::PARAM_INT);
        $stmt->bindParam(':isPinned', $properties['isPinned'], PDO::PARAM_BOOL);
        $stmt->bindParam(':uuid', $properties['uuid']);
        $stmt->execute();
    }

    /**
     * @param $result
     * @return Article
     * @throws Exception
     */
    private function resultToArticle($result): Article {
        $article = new Article();

        $article->setId($result["id"]);
        $article->setUuid($result["uuid"]);
        $article->setVersionFromString($result["version"]);
        $article->setTitle($result["title"]);
        $article->setExcerpt($result["excerpt"]);
        $article->setImageExtension($result["image_extension"]);
        $article->setUserId($result["id_user"]);

        if(isset($result["body"])) {
            $article->setBody($result["body"]);
        }

        return $article;
    }
}
