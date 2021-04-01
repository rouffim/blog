<?php

namespace model\article;

use Exception;
use model\AbstractModel;
use model\user\User;
use model\utils\StringUtils;
use model\utils\FileUtils;
use model\utils\NumberUtils;
use service\UserService;

class Article extends AbstractModel {
    private string $title;
    private ?string $excerpt;
    private string $body;
    private ?string $imageExtension;
    private int $nbViews;
    private bool $isPinned;
    private int $userId;
    private ?User $user;

    /**
     * Article constructor.
     * @throws Exception
     */
    public function __construct() {
        parent::__construct();

        $this->title = '';
        $this->excerpt = null;
        $this->body = '';
        $this->imageExtension = null;
        $this->nbViews = 0;
        $this->isPinned = false;
        $this->userId = -1;
        $this->user = null;

        $this->MODEL_IMAGE_PATH = "article/";
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    /**
     * @param string|null $excerpt
     */
    public function setExcerpt(?string $excerpt): void
    {
        if(!StringUtils::isEmpty($excerpt)) {
            $this->excerpt = $excerpt;
        } else {
            $this->excerpt = null;
        }
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string|null
     */
    public function getImageExtension(): ?string
    {
        return $this->imageExtension;
    }

    /**
     * @param string|null $imageExtension
     */
    public function setImageExtension(?string $imageExtension): void
    {
        if(!StringUtils::isEmpty($imageExtension)) {
            $this->imageExtension = $imageExtension;
        } else {
            $this->imageExtension = null;
        }
    }

    /**
     * @return string|null
     */
    public function getImagePath(): ?string
    {
        if(!is_null($this->imageExtension)) {
            try {
                return FileUtils::getModelImagePath($this, $this->imageExtension);
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * @return int
     */
    public function getNbViews(): int
    {
        return $this->nbViews;
    }

    /**
     * @param int $nbViews
     */
    public function setNbViews(int $nbViews): void
    {
        $this->nbViews = $nbViews;
    }

    /**
     * @return bool
     */
    public function isPinned(): bool
    {
        return $this->isPinned;
    }

    /**
     * @param bool $isPinned
     */
    public function setIsPinned(bool $isPinned): void
    {
        $this->isPinned = $isPinned;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return User|null
     * @throws Exception
     */
    public function getUser(): ?User
    {
        if(is_null($this->user)) {
            $this->user = UserService::getInstance()->findById($this->getUserId());

            if(is_null($this->user)) {
                throw new Exception("Article user must not be null.");
            }
        }
        return $this->user;
    }

    /**
     * @param bool $json
     * @return array
     * @throws Exception
     */
    public function toMap(bool $json = false): array {
        $map = parent::toMap($json);

        $map["title"] = $this->getTitle();
        $map["excerpt"] = $this->getExcerpt();
        $map["body"] = $this->getBody();
        $map["image_extension"] = $this->getImageExtension();
        $map["image_path"] = $this->getImagePath();
        $map["nbViews"] = $this->getNbViews();
        $map["isPinned"] = NumberUtils::booleanToInt($this->isPinned);

        $map["id_user"] = $json ?  $this->getUser()->getUuid() : $this->getUserId();
        $map["user_pseudo"] = $this->getUser()->getPseudo();
        $map["user_image"] =  $this->getUser()->getImagePath();

        return $map;
    }

}
