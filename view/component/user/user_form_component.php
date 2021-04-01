<form class="user form">
    <input type="text" name="uuid" value="{{uuid}}" class="d-none" />
    <input type="text" name="version" value="{{version_iso}}" class="d-none" />
    <input type="text" name="image_extension" value="{{image_extension}}" class="d-none" />

    <div class="form-group">
        <label for="title">email*</label>
        <input type="text" name="email" value="{{email}}" class="form-control" id="email" placeholder="email" aria-describedby="validation-email" required <?php echo (isset($_GET["uuid"]) ? 'disabled' : '') ?>>
        <div id="validation-email" class="invalid-feedback"></div>
    </div>

    <div class="form-group mt-3">
        <label for="pseudo">Pseudo*</label>
        <input type="text" name="pseudo" value="{{pseudo}}" class="form-control" id="pseudo" placeholder="pseudo" aria-describedby="validation-pseudo" required <?php echo (isset($_GET["uuid"]) ? 'disabled' : '') ?>>
        <div id="validation-pseudo" class="invalid-feedback"></div>
    </div>

    <div class="form-group mt-3">
        <label for="password">Mot de passe*</label>
        <input type="password" name="password" value="{{password}}" class="form-control" id="password" placeholder="mot de passe" aria-describedby="validation-password" required <?php echo (isset($_GET["uuid"]) ? 'disabled' : '') ?>>
        <div id="validation-password" class="invalid-feedback"></div>
    </div>

    <div class="form-group mt-3">
        <label for="password_confirm">Confirmation du mot de passe*</label>
        <input type="password" name="password_confirm" value="{{password_confirm}}" class="form-control" id="password_confirm" placeholder="confirmer mot de passe" aria-describedby="validation-password_confirm" required <?php echo (isset($_GET["uuid"]) ? 'disabled' : '') ?>>
        <div id="validation-password_confirm" class="invalid-feedback"></div>
    </div>

    <div class="form-group mt-3">
        <label for="excerpt">Avatar</label>
        <div class="d-flex">
            <div class="flex-grow-1">
                <input type="file" id="image" name="image" class="form-control" accept="image/png, image/jpeg, image/jpg, image/gif" aria-describedby="validation-image">
                <div id="validation-image" class="invalid-feedback"></div>
            </div>
            <div class="mt-1 pr-1 flex-grow-1 d-flex justify-content-end">
                <div>
                    <img src="" class="card-img-top d-none user-image preview" alt="user-image">
                    <div><i>Image actuelle</i></div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Confirmer</button>
</form>
<?php
