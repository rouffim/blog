<!doctype html>
<html lang="fr">
<head>
    <?php require('view/utils/head.php'); ?>

    <script src="/blog/resources/js/page/article/articles_page.js"></script>

    <title>Home blog</title>
</head>
<body>
<?php require('view/utils/navigation.php'); ?>

<main role="main">
    <div class="container">
        <div class="row">
            <div class="col mb-1">
                <div class="d-flex align-items-center">
                    <label for="articles-sort">Trier par: </label>
                    <select id="articles-sort" class="custom-select">
                        <option value="version desc">date de création desc</option>
                        <option value="version asc">date de création asc</option>
                        <option value="title asc">titre asc</option>
                    </select>

                    <form class="d-flex ms-3 articles-search">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col articles-container articles">

            </div>
        </div>
    </div>
</main>

<?php require('view/utils/footer.php'); ?>
<?php require('view/utils/components.php'); ?>
</body>
</html>
