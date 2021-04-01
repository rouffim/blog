<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Se connecter
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarScrollingDropdown">
        <li>
            <div class="user-login-container">
                <div>
                    <form class="form user-login-form" role="form" method="POST" accept-charset="UTF-8">
                        <div class="form-group">
                            <label class="sr-only" for="email">Email address</label>
                            <input type="email" class="form-control" id="email" placeholder="Email" name="email" aria-labelledby="validation-user-email" required>
                            <div id="validation-user-email" class="invalid-feedback"></div>
                        </div>
                        <div class="form-group mt-2">
                            <label class="sr-only" for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" aria-labelledby="validation-user-password" required>
                            <div id="validation-user-password" class="invalid-feedback"></div>
                            <div class="help-block text-right"><a href="">Mot de passe oublié ?</a></div>
                        </div>
<!--                        <div class="form-group mt-2">-->
<!--                            <label>-->
<!--                                <input type="checkbox"> keep me logged-in-->
<!--                            </label>-->
<!--                        </div>-->
                        <div class="form-group mt-2">
                            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                        </div>
                    </form>
                </div>
                <div class="bottom mt-2">
                    Pas encore inscrit ? <a href="/registration"><b>Nous rejoindre</b></a>
                </div>
            </div>
        </li>
    </ul>
</li>

