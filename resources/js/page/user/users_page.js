(function(){
    let usersContainer,
        searchForm,
        params = null,
        lock = false,
        step = 5;

    // Execute when document is loaded
    document.addEventListener('DOMContentLoaded', init, false);

    function init() {
        usersContainer = document.querySelector('.users-container');
        searchForm = document.querySelector('.users-search');

        if(usersContainer) {
            initParams();

            findUsers();

            document.querySelector('#users-sort').addEventListener("click", function() {
                let sort = this.options[this.selectedIndex].value.split(' ');
                let sortKey = sort[0];
                let sortType = sort[1];

                if(params.sortKey !== sortKey || params.sortType !== sortType) {
                    updateParams(sortKey, sortType);
                    findUsers();
                }
            });

            if(searchForm) {
                searchForm.addEventListener("submit", function (e) {
                    e.preventDefault();
                    let val = searchForm.querySelector('input').value;
                    if(val) {
                        updateParams(null, null, val);
                        findUsers();
                    }
                });
            }

            document.addEventListener('scroll', function() {
                if(!lock && window.scrollY >= usersContainer.offsetTop + usersContainer.offsetHeight - window.innerHeight) {
                    params.index += step;
                    findUsers();
                }
            });
        }
    }

    function initParams() {
        params = new SearchPageable();
        params.fromUri();
        params.index = 0;
        params.offset = step;
        params.setSortKey(params.sortKey, UserSortKeys.REGISTRATION_DATE);
        params.setSortType(params.sortType, SortKeyTypes.DESC);
        params.updateUri();

        if(searchForm) {
            searchForm.querySelector('input').value = params.search;
        }
    }

    function updateParams(sortKey, sortType, search) {
        params.index = 0;
        params.offset = step;
        params.setSortKey(sortKey, UserSortKeys.REGISTRATION_DATE);
        params.setSortType(sortType, SortKeyTypes.DESC);
        params.search = search;
        params.updateUri();
        usersContainer.innerHTML = '';
        lock = false;
    }

    function findUsers() {
        console.log(params.toParams());
        UserRequest.findUsers(usersContainer, params.toParams())
            .catch(() => lock = true);
    }


})();
