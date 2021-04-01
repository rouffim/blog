(function(){
    let form;
    let formContainer;

    // Execute when document is loaded
    document.addEventListener('DOMContentLoaded', init, false);

    function init() {
        formContainer = document.querySelector('#user-change-role-form-container');

        if(formContainer) {
            let displayType = UserDisplayer.TYPE_CHANGE_ROLE_FORM;
            let uuid = RequestHelper.getUriParam('uuid');
            uuid = uuid ? uuid : ' ';

            UserRequest.findUser(formContainer, uuid, displayType)
                .then(() => {
                    form = formContainer.querySelector('.user.' + displayType);

                    if(form) {
                        form.addEventListener("submit", (e) => {
                            e.preventDefault();
                            UserRequest.changeUserRole(form);
                        });
                    }
                });
        }
    }


})();
