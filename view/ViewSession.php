<?php


class ViewSession
{
    static int $VIEW_ARTICLE = 0;
    static int $ADD_ARTICLE = 1;
    static int $EDIT_OWN_ARTICLE = 2;
    static int $EDIT_ALL_ARTICLE = 3;
    static int $REMOVE_OWN_ARTICLE = 4;
    static int $REMOVE_ALL_ARTICLE = 5;
    static int $CHANGE_ROLE = 6;
    static array $ROLES = [0, 1, 2];
    static array $ROLE_NAMES = [
        0 => "viewer",
        1 => "auteur",
        2 => "admin"
    ];

    /**
     * ViewSession constructor.
     */
    private function __construct() {

    }

    /**
     * @return bool
     */
    public static function isUserLogged(): bool {
        return isset($_SESSION["user"]);
    }

    /**
     * @return array|null
     */
    public static function getUserSession(): ?array {
        return ViewSession::isUserLogged() ? $_SESSION["user"] : null;
    }

    /**
     * @param string $data
     * @return bool
     */
    public static function hasUserSessionData(string $data): bool {
        return ViewSession::isUserLogged() && isset(ViewSession::getUserSession()[$data]);
    }

    /**
     * @param string $data
     * @return mixed|null
     */
    public static function getUserSessionData(string $data) {
        return ViewSession::isUserLogged() && isset(ViewSession::getUserSession()[$data]) ?
            ViewSession::getUserSession()[$data] : null;
    }

    /**
     * @param int $permissionId
     * @return bool
     */
    public static function hasPermission(int $permissionId): bool {
        if(ViewSession::isUserLogged() && is_array(ViewSession::getUserSessionData("permissions"))) {
            foreach (ViewSession::getUserSessionData("permissions") as $permission) {
                if($permission == $permissionId) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     *
     */
    public static function displayRoleOptions() {
        $userRole = ViewSession::getUserSessionData('id_role');

        if(is_int($userRole)) {

            foreach (ViewSession::$ROLES as $role) {
                if ($userRole >= $role) {
                    echo '<option value="' . $role . '">' . ViewSession::$ROLE_NAMES[$role] . '</option>';
                }
            }
        }
    }
}
