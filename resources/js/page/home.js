(function(){
    let pinnedArticlesContainer = null,
        topArticlesContainer = null,
        topUsersContainer = null;

    // Execute when document is loaded
    document.addEventListener('DOMContentLoaded', init, false);

    function init() {
        initPinnedArticles();
        initTopArticles();
        initTopUsers();
    }

    function initPinnedArticles() {
        pinnedArticlesContainer = document.querySelector('.pinned-articles');

        //ArticleRequest.findArticles(pinnedArticlesContainer);
    }

    function initTopArticles() {
        topArticlesContainer = document.querySelector('.top-articles');

        let params = new SearchPageable();
        params.index = 0;
        params.offset = 5;
        params.sortKey = ArticleSortKeys.VERSION;
        params.sortType = SortKeyTypes.DESC;

        ArticleRequest.findArticles(topArticlesContainer, params.toParams());
    }

    function initTopUsers() {
        topUsersContainer = document.querySelector('.top-users');

        //Article.findArticles();
    }

})();
