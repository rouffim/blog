(function(){
    let form;
    let formContainer;

    // Execute when document is loaded
    document.addEventListener('DOMContentLoaded', init, false);

    function init() {
        formContainer = document.querySelector('#article-form-container');

        if(formContainer) {
            let displayType = ArticleDisplayer.TYPE_FORM;
            let uuid = RequestHelper.getUriParam('uuid');
            uuid = uuid ? uuid : ' ';

            ArticleRequest.findArticle(formContainer, uuid, displayType)
                .then(() => {
                    form = formContainer.querySelector('.article.' + displayType);

                    if(form) {
                        form.addEventListener("submit", (e) => {
                            e.preventDefault();
                            ArticleRequest.saveArticle(form);
                        });
                    }
                });
        }
    }


})();
