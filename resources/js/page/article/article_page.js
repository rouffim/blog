(function(){
    let articleContainer;

    // Execute when document is loaded
    document.addEventListener('DOMContentLoaded', init, false);

    function init() {
        articleContainer = document.querySelector('#article-container');

        if(articleContainer) {
            let uuid = RequestHelper.getUriParam('uuid');

            ArticleRequest.findArticle(articleContainer, uuid).then(() => {
                let deleteBtn = articleContainer.querySelector(".delete-article");
                if(deleteBtn && deleteBtn.style.display !== 'none') {
                    deleteBtn.addEventListener("click", (e) => {
                        ArticleRequest.deleteArticle(uuid);
                    });
                }
            });
        }
    }


})();
