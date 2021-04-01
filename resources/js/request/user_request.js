class UserRequest {

    static findUser(container, uuid, type = UserDisplayer.TYPE_SMALL) {
        return new Promise((resolve, reject) => {
            if(container && uuid) {
                AjaxRequest.makeRequest('/api/user?uuid=' + uuid)
                    .then(function (response) {
                        UserDisplayer.displayUser(response, container, type);
                        resolve(response);
                    })
                    .catch(function (err) {
                        if(type === UserDisplayer.TYPE_FORM) {
                            UserDisplayer.displayUser(null, container, type);
                            resolve();
                        } else {
                            console.error('Error with find user : ', err);
                            reject();
                        }
                    });
            }
        });
    }

    static findUsers(container, params, type = UserDisplayer.TYPE_SMALL) {
        return new Promise((resolve, reject) => {
            if(container) {
                return AjaxRequest.makeRequest('/api/user/all' + params)
                    .then(function (response) {
                        UserDisplayer.displayUsers(response, container, type);
                        resolve();
                    })
                    .catch(function (err) {
                        console.error('Error with find users : ', err);
                        reject();
                    });
            }
        });
    }

    static saveUser(form) {
        return new Promise((resolve, reject) => {
            if(form) {
                return AjaxRequest.makeRequest('/api/user', 'POST', form)
                    .then(function (response) {
                        DomManager.handleFormSubmitSuccess(form);
                        resolve();
                    })
                    .catch(function (err) {
                        console.error('Error with save user : ', err);
                        DomManager.handleFormSubmitError(form, err);
                        reject();
                    });
            }
        });
    }

    static changeUserRole(form) {
        return new Promise((resolve, reject) => {
            if(form) {
                return AjaxRequest.makeRequest('/api/user/role', 'POST', form)
                    .then(function (response) {
                        DomManager.handleFormSubmitSuccess(form);
                        resolve();
                    })
                    .catch(function (err) {
                        console.error('Error with change role user : ', err);
                        DomManager.handleFormSubmitError(form, err);
                        reject();
                    });
            }
        });
    }

    static deleteUser(uuid) {
        return new Promise((resolve, reject) => {
            if(uuid) {
                return AjaxRequest.makeRequest('/api/user?uuid=' + uuid, 'DELETE')
                    .then(function (response) {
                        AlertManager.displaySuccessAlert();

                        setTimeout(() => {
                            resolve();
                            window.location.href = '/';
                        }, 750);
                    })
                    .catch(function (err) {
                        console.error('Error with delete user : ', err);
                        AlertManager.displayErrorAlert();
                        reject();
                    });
            }
        });
    }

}

class UserSortKeys {
    static REGISTRATION_DATE = 'registration_date';
    static PSEUDO = 'pseudo';
    static EMAIL = 'email';
}
