<?php
namespace model\user;

use Exception;
use model\TechnicalModel;
use service\TechnicalService;

class Role extends TechnicalModel
{
    static int $VIEWER = 0;
    static int $WRITER = 1;
    static int $ADMIN = 2;
    static array $ROLES = [0, 1, 2];

    static string $TECHNICAL_NAME = "role";

    private ?array $permissions;

    /**
     * Role constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        parent::__construct($id, $name);
        $this->permissions = null;
    }

    /**
     * @param int $roleId
     * @return bool
     */
    public static function isValid(int $roleId): bool {
        return in_array($roleId, Role::$ROLES);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getPermissions(): array
    {
        if(is_null($this->permissions)) {
            $this->permissions = TechnicalService::getInstance()->getPermissionsRole($this->id);
        }
        return $this->permissions;
    }

    /**
     * @param int $permissionId
     * @return bool
     * @throws Exception
     */
    public function hasPermission(int $permissionId): bool {
        if(!is_null($this->getPermissions())) {
            foreach($this->getPermissions() as $permission) {
                if($permissionId == $permission->getId()) {
                    return true;
                }
            }
        }
        return false;
    }


}
