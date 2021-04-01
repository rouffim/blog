<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="/blog/resources/images/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="/articles">Articles</a>
                    </li>
                    <li class="nav-item <?php echo (ViewSession::hasPermission(ViewSession::$ADD_ARTICLE) ? '' : 'd-none'); ?>">
                        <a class="nav-link" href="/article/edit">Créer</a>
                    </li>
                    <li class="nav-item <?php echo (ViewSession::hasPermission(ViewSession::$CHANGE_ROLE) ? '' : 'd-none'); ?>">
                        <a class="nav-link" href="/users">Utilisateurs</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0 ms-auto">
                    <?php
                        require('view/utils/' . (ViewSession::isUserLogged() ? 'user_actions' : 'login') . '.php');
                    ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
