<!doctype html>
<html lang="fr">
<head>
    <?php require('view/utils/head.php'); ?>

    <script src="/blog/resources/js/page/user/users_page.js"></script>

    <title>Home blog</title>
</head>
<body>
<?php require('view/utils/navigation.php'); ?>

<main role="main">
    <div class="container">
        <div class="row">
            <div class="col mb-1">
                <div class="d-flex align-items-center">
                    <label for="users-sort">Trier par: </label>
                    <select id="users-sort" class="custom-select">
                        <option value="registration_date desc">date d'inscription desc</option>
                        <option value="registration_date asc">date d'inscription asc</option>
                        <option value="pseudo asc">pseudo asc</option>
                        <option value="email asc">email asc</option>
                    </select>

                    <form class="d-flex ms-3 users-search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col users-container users">

            </div>
        </div>
    </div>
</main>

<?php require('view/utils/footer.php'); ?>
<?php require('view/utils/components.php'); ?>
</body>
</html>
