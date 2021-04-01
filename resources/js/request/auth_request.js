class AuthRequest {

    static login(form) {
        return new Promise((resolve, reject) => {
            if(form) {
                return AjaxRequest.makeRequest('/api/auth/login', 'POST', form)
                    .then(function (response) {
                        DomManager.handleFormSubmitSuccess(form);
                        resolve();
                    })
                    .catch(function (err) {
                        console.error('Error with login : ', err);
                        DomManager.handleFormSubmitError(form, err);
                        reject();
                    });
            }
        });
    }

    static logout() {
        return new Promise((resolve, reject) => {
            return AjaxRequest.makeRequest('/api/auth/logout', 'POST')
                .then(function (response) {
                    window.location.href = '/';
                    resolve();
                })
                .catch(function (err) {
                    console.error('Error with logout : ', err);
                    AlertManager.displayErrorAlert();
                    reject();
                });
        });
    }
}
