(function(){
    let articlesContainer,
        articleForm,
        params,
        lock = false,
        step = 5;

    // Execute when document is loaded
    document.addEventListener('DOMContentLoaded', init, false);

    function init() {
        articlesContainer = document.querySelector('.articles-container');
        articleForm = document.querySelector('.articles-search');

        if(articlesContainer) {
            initParams();

            findArticles();

            document.querySelector('#articles-sort').addEventListener("click", function() {
                let sort = this.options[this.selectedIndex].value.split(' ');
                let sortKey = sort[0];
                let sortType = sort[1];

                if(params.sortKey !== sortKey || params.sortType !== sortType) {
                    updateParams(sortKey, sortType);
                    findArticles();
                }
            });

            if(articleForm) {
                articleForm.addEventListener("submit", function (e) {
                    e.preventDefault();
                    let val = articleForm.querySelector('input').value;
                    if(val) {
                        updateParams(null, null, val);
                        findArticles();
                    }
                });
            }

            document.addEventListener('scroll', function() {
                if(!lock && window.scrollY >= articlesContainer.offsetTop + articlesContainer.offsetHeight - window.innerHeight) {
                    params.index += step;
                    findArticles();
                }
            });
        }
    }

    function initParams() {
        params = new SearchPageable();
        params.fromUri();
        params.index = 0;
        params.offset = step;
        params.setSortKey(params.sortKey, ArticleSortKeys.VERSION);
        params.setSortType(params.sortType, SortKeyTypes.DESC);
        params.updateUri();

        if(articleForm) {
            articleForm.querySelector('input').value = params.search;
        }
    }

    function updateParams(sortKey, sortType, search) {
        params.index = 0;
        params.offset = step;
        params.setSortKey(sortKey, ArticleSortKeys.VERSION);
        params.setSortType(sortType, SortKeyTypes.DESC);
        params.search = search;
        params.updateUri();
        articlesContainer.innerHTML = '';
        lock = false;
    }

    function findArticles() {
        ArticleRequest.findArticles(articlesContainer, params.toParams())
            .catch(() => lock = true);
    }


})();
