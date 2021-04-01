<div class="d-none user-session-uuid"><?php echo (ViewSession::hasUserSessionData("uuid") ? ViewSession::getUserSessionData("uuid") : "-1") ?></div>
<div class="d-none user-session-role"><?php echo (ViewSession::hasUserSessionData("id_role") ? ViewSession::getUserSessionData("id_role") : "-1") ?></div>
<div class="d-none user-session-permissions">
<?php
    if(ViewSession::hasUserSessionData("permissions")) {
        foreach (ViewSession::getUserSessionData("permissions") as $permission) {
            echo '<div class="d-none user-session-permission-' . $permission . '"></div>';
        }
    }
?>
</div>
