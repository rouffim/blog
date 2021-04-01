class ArticleRequest {

    static findArticle(container, uuid, type = ArticleDisplayer.TYPE_NORMAL) {
        return new Promise((resolve, reject) => {
            if(container && uuid) {
                AjaxRequest.makeRequest('/api/article?uuid=' + uuid)
                    .then(function (response) {
                        ArticleDisplayer.displayArticle(response, container, type);
                        resolve(response);
                    })
                    .catch(function (err) {
                        if(type === ArticleDisplayer.TYPE_FORM) {
                            ArticleDisplayer.displayArticle(null, container, type);
                            resolve();
                        } else {
                            console.error('Error with find article : ', err);
                            reject();
                        }
                    });
            }
        });
    }

    static findArticles(container, params, type = ArticleDisplayer.TYPE_SMALL) {
        return new Promise((resolve, reject) => {
            if(container) {
                return AjaxRequest.makeRequest('/api/article/all' + params)
                    .then(function (response) {
                        ArticleDisplayer.displayArticles(response, container, type);
                        resolve();
                    })
                    .catch(function (err) {
                        console.error('Error with find articles : ', err);
                        reject();
                    });
            }
        });
    }

    static saveArticle(form) {
        return new Promise((resolve, reject) => {
            if(form) {
                return AjaxRequest.makeRequest('/api/article', 'POST', form)
                    .then(function (response) {
                        DomManager.handleFormSubmitSuccess(form);
                        resolve();
                    })
                    .catch(function (err) {
                        console.error('Error with save article : ', err);
                        DomManager.handleFormSubmitError(form, err);
                        reject();
                    });
            }
        });
    }

    static deleteArticle(uuid) {
        return new Promise((resolve, reject) => {
            if(uuid) {
                return AjaxRequest.makeRequest('/api/article?uuid=' + uuid, 'DELETE')
                    .then(function (response) {
                        AlertManager.displaySuccessAlert();

                        setTimeout(() => {
                            resolve();
                            window.location.href = '/';
                        }, 750);
                    })
                    .catch(function (err) {
                        console.error('Error with delete article : ', err);
                        AlertManager.displayErrorAlert();
                        reject();
                    });
            }
        });
    }

}

class ArticleSortKeys {
    static VERSION = 'version';
    static TITLE = 'title';
}
