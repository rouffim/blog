<?php

namespace model\user;

use DateTime;
use Exception;
use model\AbstractModel;
use model\utils\DateUtils;
use model\utils\FileUtils;
use model\utils\StringUtils;
use service\TechnicalService;

class User extends AbstractModel {
    private string $email;
    private string $pseudo;
    private string $password;
    private int $roleId;
    private ?Role $role;
    private ?string $imageExtension;
    private DateTime $registrationDate;

    /**
     * User constructor.
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->email = '';
        $this->pseudo = '';
        $this->password = '';
        $this->roleId = Role::$VIEWER;
        $this->role = null;
        $this->imageExtension = null;
        $this->registrationDate = DateUtils::now();

        $this->MODEL_IMAGE_PATH = "user/";
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * @param string $pseudo
     */
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = $pseudo;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getImageExtension(): ?string
    {
        return $this->imageExtension;
    }

    /**
     * @return DateTime
     */
    public function getRegistrationDate(): DateTime
    {
        return $this->registrationDate;
    }

    /**
     * @param DateTime $registrationDate
     */
    public function setRegistrationDate(DateTime $registrationDate): void
    {
        $this->registrationDate = $registrationDate;
    }

    /**
     * @param string $registrationDate
     * @throws Exception
     */
    public function setRegistrationDateFromString(string $registrationDate): void
    {
        $this->registrationDate = new DateTime($registrationDate);
    }

    /**
     * @return string
     */
    public function getRegistrationDateToString(): string
    {
        return $this->registrationDate->format("d-m-Y H:i:s");
    }

    /**
     * @return int
     */
    public function getRoleId(): int
    {
        return $this->roleId;
    }

    /**
     * @param int $roleId
     */
    public function setRoleId(int $roleId): void
    {
        $this->roleId = $roleId;
    }

    /**
     * @return Role|null
     * @throws Exception
     */
    public function getRole(): Role
    {
        if(is_null($this->role)) {
            $this->role = TechnicalService::getInstance()->getRole($this->roleId);
        }
        return $this->role;
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
     * @param bool $json
     * @return array
     * @throws Exception
     */
    public function toMap(bool $json = false): array {
        $map = parent::toMap($json);

        $map["email"] = $this->getEmail();
        $map["pseudo"] = $this->getPseudo();
        $map["image_extension"] = $this->getImageExtension();
        $map["id_role"] = $this->getRoleId();
        $map["role_name"] = $this->getRole()->getName();
        $map["image_path"] = $this->getImagePath();
        $map["registration_date"] = $this->getRegistrationDateToString();
        $map["permissions"] = array_map(function($permission) {
            return $permission->getId();
        }, $this->getRole()->getPermissions());

        if(!$json) {
            $map["password_hash"] = $this->getPassword();
        }

        return $map;
    }
}
