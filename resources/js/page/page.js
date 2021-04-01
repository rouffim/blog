(function(){

    // Execute when document is loaded
    document.addEventListener('DOMContentLoaded', init, false);

    function init() {
        initListeners();
    }

    function initListeners() {
        let userLoginForms = document.querySelectorAll('.user-login-form');
        let userLogoutBtns = document.querySelectorAll('.user-logout');

        for(let form of userLoginForms) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                AuthRequest.login(form);
            });
        }

        for(let btn of userLogoutBtns) {
            btn.addEventListener('click', function() {
                AuthRequest.logout();
            });
        }
    }

})();
