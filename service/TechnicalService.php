<?php

namespace service;

use Exception;
use model\TechnicalModel;
use model\user\Permission;
use model\user\Role;
use PDO;

class TechnicalService extends Service {
    private static ?TechnicalService $_instance = null;

    private ?array $permissions = null;
    private ?array $roles = null;

    /**
     * TechnicalService constructor.
     */
    private function __construct() {
        parent::__construct();
    }

    /**
     * @return TechnicalService|null
     */
    public static function getInstance() {
        if(is_null(self::$_instance)) {
            self::$_instance = new TechnicalService();
        }

        return self::$_instance;
    }


    /**
     * @param string $technicalName
     * @param int $id
     * @return TechnicalModel
     * @throws Exception
     */
    private function find(string $technicalName, int $id): TechnicalModel {
        try {
            $this->connectToDb();
            $stmt = $this->db->prepare("SELECT id, name FROM :technical where id = :id LIMIT 1");
            $stmt->bindParam(':technical', $technicalName, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();

            if ($result) {
                $technical = $this->resultToTechnical($technicalName, $result);

                $this->disconnectFromDb();
            } else {
                $this->disconnectFromDb();
                throw new NotFoundException();
            }

            return $technical;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param string $technicalName
     * @param string|null $technicalJoinName
     * @param int|null $technicalJoinId
     * @return array
     * @throws Exception
     */
    private function findAll(string $technicalName, ?string $technicalJoinName = null, ?int $technicalJoinId = null): array {
        try {
            $technicals = array();
            $this->connectToDb();
            $isJoin = !is_null($technicalJoinName) && !is_null($technicalJoinId);
            $sql = "SELECT id, name FROM $technicalName ";

            if($isJoin) {
                $joinTableName = $technicalJoinName . "_has_" . $technicalName;
                $sql .= "inner join $joinTableName on $joinTableName.id_$technicalName = $technicalName.id ";
                $sql .= "where $joinTableName.id_$technicalJoinName = $technicalJoinId";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll();

            if ($results) {
                foreach ($results as $row) {
                    array_push($technicals, $this->resultToTechnical($technicalName, $row));
                }
            }

            $this->disconnectFromDb();

            return  $technicals;
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getPermissions(): array {
        if(is_null($this->permissions)) {
            $this->permissions = $this->findAll('permission');
        }

        return $this->permissions;
    }

    /**
     * @param int
     * @return Permission|null
     * @throws Exception
     */
    public function getPermission(int $permissionId): ?Permission {
        foreach($this->getPermissions() as $permission) {
            if ($permissionId == $permission->getId()) {
                return $permission;
            }
        }
        return null;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getRoles(): array {
        if(is_null($this->roles)) {
            $this->roles = $this->findAll('role');
        }

        return $this->roles;
    }

    /**
     * @param int
     * @return Role|null
     * @throws Exception
     */
    public function getRole(int $roleId): ?Role {
        foreach($this->getRoles() as $role) {
            if ($roleId == $role->getId()) {
                return $role;
            }
        }
        throw new Exception("Given roleId ' . $roleId . ' doesn't exists.");
    }

    /**
     * @param int
     * @return array
     * @throws Exception
     */
    public function getPermissionsRole(int $role): array {
        return $this->findAll('permission', 'role', $role);
    }


    /**
     * @param $technicalName
     * @param $technical
     * @return TechnicalModel|null
     */
    private function resultToTechnical($technicalName, $technical): ?TechnicalModel {
        switch (true) {
            case Permission::$TECHNICAL_NAME == $technicalName :
                return new Permission($technical["id"], $technical["name"]);
            case Role::$TECHNICAL_NAME == $technicalName :
                return new Role($technical["id"], $technical["name"]);
            default:
                return null;
        }
    }
}
