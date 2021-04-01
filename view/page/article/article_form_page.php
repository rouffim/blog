<!doctype html>
<html lang="fr">
<head>
    <?php require('view/utils/head.php'); ?>

    <script src="/blog/resources/js/page/article/article_form.js"></script>

    <title>Home blog</title>
</head>
<body>
<?php require('view/utils/navigation.php'); ?>

<main role="main">
    <div class="container">
        <div class="row">
            <?php
                if((isset($_GET["uuid"]) && ViewSession::hasPermission(ViewSession::$EDIT_OWN_ARTICLE)) ||
                  (!isset($_GET["uuid"]) && ViewSession::hasPermission(ViewSession::$ADD_ARTICLE))) {
                    echo '<div id="article-form-container" class="col"></div>';
                } else if(!ViewSession::isUserLogged()) {
                    require('view/component/errors/user_not_logged.php');
                } else {
                    require('view/component/errors/user_not_authorized.php');
                }
            ?>
        </div>
    </div>
</main>

<?php require('view/utils/footer.php'); ?>
<?php require('view/utils/components.php'); ?>
</body>
</html>
