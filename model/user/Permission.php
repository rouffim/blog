<?php
namespace model\user;


use model\TechnicalModel;

class Permission extends TechnicalModel
{
    static int $VIEW_ARTICLE = 0;
    static int $ADD_ARTICLE = 1;
    static int $EDIT_OWN_ARTICLE = 2;
    static int $EDIT_ALL_ARTICLE = 3;
    static int $REMOVE_OWN_ARTICLE = 4;
    static int $REMOVE_ALL_ARTICLE = 5;
    static int $CHANGE_ROLE = 6;
    static array $PERMISSIONS = [0, 1, 2, 3, 4, 5, 6];

    static string $TECHNICAL_NAME = "permission";

    /**
     * Permission constructor.
     * @param $id
     * @param $name
     */
    public function __construct($id, $name)
    {
        parent::__construct($id, $name);
    }

    /**
     * @param int $permissionId
     * @return bool
     */
    public static function isValid(int $permissionId): bool {
        return in_array($permissionId, Permission::$PERMISSIONS);
    }


}
