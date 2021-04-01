<!doctype html>
<html lang="fr">
<head>
    <?php require('view/utils/head.php'); ?>

    <script src="/blog/resources/js/page/user/user_form.js"></script>

    <title>Home blog</title>
</head>
<body>
<?php require('view/utils/navigation.php'); ?>

<main role="main">
    <div class="container">
        <div class="row">
            <?php
                if(isset($_GET["uuid"]) && (!ViewSession::isUserLogged() || ViewSession::getUserSessionData('uuid') != $_GET["uuid"])) {
                    require('view/component/errors/user_not_authorized.php');
                } else if(!isset($_GET["uuid"]) && ViewSession::isUserLogged()) {
                    require('view/component/errors/user_already_logged.php');
                } else {
                    echo '<div id="user-form-container" class="col"></div>';
                }
            ?>
        </div>
    </div>
</main>

<?php require('view/utils/footer.php'); ?>
<?php require('view/utils/components.php'); ?>
</body>
</html>
