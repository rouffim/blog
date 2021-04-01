<!doctype html>
<html lang="fr">
<head>
    <?php require('view/utils/head.php'); ?>

    <script src="/blog/resources/js/page/home.js"></script>

    <title>Home blog</title>
</head>
<body>
<?php require('view/utils/navigation.php'); ?>

<main role="main">
    <div class="container">
        <div class="row">
            <div class="col">
                <?php
                    if(!ViewSession::isUserLogged()) {
                        require('view/component/auth/login_form_component.php');
                    } else {
                        require('view/component/errors/user_already_logged.php');
                    }
                ?>
            </div>
        </div>
    </div>
</main>

<?php require('view/utils/footer.php'); ?>
<?php require('view/utils/components.php'); ?>
</body>
</html>
