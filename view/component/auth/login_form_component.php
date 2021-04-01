<form class="form user-login-form" role="form" method="POST" accept-charset="UTF-8">
    <div class="form-group">
        <label class="sr-only" for="email2">Email address</label>
        <input type="email" class="form-control" id="email2" placeholder="Email" name="email" aria-labelledby="validation-user2-email" required>
        <div id="validation-user2-email" class="invalid-feedback"></div>
    </div>
    <div class="form-group mt-2">
        <label class="sr-only" for="password2">Password</label>
        <input type="password" class="form-control" id="password2" name="password" placeholder="Mot de passe" aria-labelledby="validation-user2-password" required>
        <div id="validation-user2-password" class="invalid-feedback"></div>
        <div class="help-block text-right"><a href="">Mot de passe oublié ?</a></div>
    </div>
<!--    <div class="form-group mt-2">-->
<!--        <label>-->
<!--            <input type="checkbox"> keep me logged-in-->
<!--        </label>-->
<!--    </div>-->
    <div class="form-group mt-2">
        <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
    </div>
</form>
