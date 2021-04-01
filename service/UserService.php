<?php

namespace service;

use Exception;
use InvalidArgumentException;
use model\AbstractModel;
use model\user\User;
use model\exception\NotFoundException;
use model\Pageable;
use model\utils\StringUtils;
use model\utils\UuidUtils;
use PDO;

class UserService extends ResourceService {
    private static ?UserService $_instance = null;
    private string $SELECT_QUERY = "select id, uuid, version, email, pseudo, password_hash, image_extension, registration_date, id_role from user ";

    /**
     * UserService constructor.
     */
    private function __construct() {
        parent::__construct();
    }

    /**
     * @return UserService|null
     */
    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new UserService();
        }

        return self::$_instance;
    }


    /**
     * @param string $uuid
     * @return User
     * @throws Exception
     */
    public function find(string $uuid): User {
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
                $user = $this->resultToUser($result);

                $this->disconnectFromDb();
            } else {
                $this->disconnectFromDb();
                throw new NotFoundException();
            }

            return $user;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param int $id
     * @return User
     * @throws Exception
     */
    public function findById(int $id): User {
        try {
            $this->connectToDb();
            $stmt = $this->db->prepare("$this->SELECT_QUERY where id = :id LIMIT 1");
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();

            if ($result) {
                $user = $this->resultToUser($result);

                $this->disconnectFromDb();
            } else {
                $this->disconnectFromDb();
                throw new NotFoundException();
            }

            return $user;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $email
     * @return User
     * @throws Exception
     */
    public function findByEmail(string $email): User {
        if(!StringUtils::isEmail($email)) {
            throw new InvalidArgumentException('Email is not valid');
        }

        try {
            $this->connectToDb();
            $stmt = $this->db->prepare("$this->SELECT_QUERY where email = :email LIMIT 1");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();

            if ($result) {
                $user = $this->resultToUser($result);

                $this->disconnectFromDb();
            } else {
                $this->disconnectFromDb();
                throw new NotFoundException();
            }

            return $user;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $pseudo
     * @return User
     * @throws Exception
     */
    public function findByPseudo(string $pseudo): User {
        if(StringUtils::isEmpty($pseudo)) {
            throw new InvalidArgumentException('Pseudo is not valid');
        }

        try {
            $this->connectToDb();
            $stmt = $this->db->prepare("$this->SELECT_QUERY where pseudo = :pseudo LIMIT 1");
            $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();

            if ($result) {
                $user = $this->resultToUser($result);

                $this->disconnectFromDb();
            } else {
                $this->disconnectFromDb();
                throw new NotFoundException();
            }

            return $user;
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
            $users = array();
            $this->connectToDb();

            $sql = $this->SELECT_QUERY;
            $sql = $this->searchToSql($sql, 'pseudo', $search);
            $sql = $this->pageableToSql($sql, $pageable);

            $stmt = $this->db->prepare($sql);
            $this->bindSearchParam($stmt, $search);
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($results) {
                foreach ($results as $row) {
                    array_push($users, $this->resultToUser($row));
                }
            }

            $this->disconnectFromDb();

            return  $users;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param AbstractModel $user
     * @param bool $update
     * @throws Exception
     */
    public function save(AbstractModel $user, bool $update = false) {
        if(is_null($user)) {
            throw new InvalidArgumentException('user is null');
        }
        if(!($user instanceof User)) {
            throw new InvalidArgumentException('user is not an User');
        }

        try {
            $this->connectToDb();
            $this->db->beginTransaction();

            if($update) {
                $this->performUpdate($user);
            } else {
                $this->performSave($user);
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
     * @throws Exception
     */
    public function delete(string $uuid) {
        if(!UuidUtils::isValidUuid($uuid)) {
            throw new InvalidArgumentException('Uuid is not valid');
        }

        try {
            $this->connectToDb();
            $stmt = $this->db->prepare("delete FROM user where uuid = :uuid");
            $stmt->bindParam(':uuid', $uuid);

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
     * @param User $user
     * @throws Exception
     */
    private function performSave(User $user) {
        $properties = $user->toMap();
        $stmt = $this->db->prepare('INSERT INTO user(uuid, email, pseudo, password_hash, image_extension, id_role) VALUES (:uuid, :email, :pseudo, :password_hash, :image_extension, :id_role)');

        $stmt->bindParam(':uuid', $properties['uuid'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $properties['email'], PDO::PARAM_STR);
        $stmt->bindParam(':pseudo', $properties['pseudo'], PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $properties['password_hash'], PDO::PARAM_STR);
        $stmt->bindParam(':image_extension', $properties['image_extension'], PDO::PARAM_STR);
        $stmt->bindParam(':id_role', $properties['id_role'], PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * @param User $user
     * @throws Exception
     */
    private function performUpdate(User $user) {
        $properties = $user->toMap();
        $stmt = $this->db->prepare('UPDATE user SET image_extension = :image_extension, id_role = :id_role where uuid = :uuid');

        $stmt->bindParam(':image_extension', $properties['image_extension'], PDO::PARAM_STR);
        $stmt->bindParam(':id_role', $properties['id_role'], PDO::PARAM_INT);
        $stmt->bindParam(':uuid', $properties['uuid'], PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * @param $result
     * @return User
     * @throws Exception
     */
    private function resultToUser($result): User {
        $user = new User();

        $user->setId($result["id"]);
        $user->setUuid($result["uuid"]);
        $user->setVersionFromString($result["version"]);
        $user->setPseudo($result["pseudo"]);
        $user->setEmail($result["email"]);
        $user->setPassword($result["password_hash"]);
        $user->setImageExtension($result["image_extension"]);
        $user->setRegistrationDateFromString($result["registration_date"]);
        $user->setRoleId($result["id_role"]);

        return $user;
    }
}
