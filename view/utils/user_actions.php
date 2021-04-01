<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php
            if(!empty(ViewSession::getUserSessionData("image_path"))) {
                echo '<img src="/blog/' . ViewSession::getUserSessionData("image_path") . '" class="rounded-circle user-avatar small me-1" alt="user-image">';
            }
            echo ViewSession::getUserSessionData("pseudo");
        ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarScrollingDropdown">
        <li><a class="dropdown-item" href="/registration?uuid=<?php echo ViewSession::getUserSessionData('uuid') ?>">Modifier son profil</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><button class="dropdown-item user-logout" type="button">Déconnexion</button></li>
    </ul>
</li>

