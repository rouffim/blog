<form class="user change-role-form">
    <input type="text" name="uuid" value="{{uuid}}" class="d-none" />

    <h3>Modifier le role de {{pseudo}} ({{email}})</h3>

    <div class="form-group mt-3 <?php echo (ViewSession::hasPermission(ViewSession::$CHANGE_ROLE) ? '' : 'd-none') ?>">
        <label for="user-role">Role</label>
        <select class="form-control" id="user-role" name="role" aria-labelledby="validation-user-role">
            <option value="-1">Sélectionner un rôle</option>
            <?php ViewSession::displayRoleOptions() ?>
        </select>
        <div id="validation-user-role" class="invalid-feedback"></div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Confirmer</button>
</form>
