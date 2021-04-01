class AjaxRequest {

    static makeRequest(request, method = "GET", form) {
        return new Promise((resolve, reject) => {
            let xhttp = new XMLHttpRequest();

            xhttp.open(method, request);
            xhttp.onload = function () {
                if (this.status >= 200 && this.status < 300) {
                    resolve(this.responseText);
                } else {
                    AjaxRequest.handleStatus(this.status);

                    reject({
                        status: this.status,
                        response: this.responseText
                    });
                }
            };
            xhttp.onerror = function () {
                AjaxRequest.handleStatus(this.status);

                reject({
                    status: this.status,
                    response: this.responseText
                });
            };

            if(form) {
                xhttp.send(new FormData(form));
            } else {
                xhttp.send();
            }
        });
    }

    static handleStatus(status) {
        switch (status) {
            case 401 :
                window.location.href = '/login';
                break;
        }
    }

}

class Pageable {

    toParams(params, full = true) {
        params = params ? params : '';

        if(full) {
            params += this.createParam("index");
            params += this.createParam("offset");
        }
        params += this.createParam("sortKey");
        params += this.createParam("sortType");

        if(params.length > 0) {
            params = '?' + params.substring(1);
        }

        return params;
    }

    createParam(param) {
        return this[param] != null ? ('&' + param + '=' + this[param]) : '';
    }

    updateUri() {
        if (window.history.replaceState) {
            let uri = document.location.href.split('?');
            window.history.replaceState({}, null, uri[0] + this.toParams(null, false));
        }
    }

    fromUri() {
        let uri = new URL(window.location.href);

        this.sortKey = uri.searchParams.get('sortKey');
        this.sortType = uri.searchParams.get('sortType');

        return uri;
    }


    get index() {
        return this._index;
    }

    set index(index) {
        this._index = index;
    }

    get offset() {
        return this._offset;
    }

    set offset(offset) {
        this._offset = offset;
    }

    get sortKey() {
        return this._sortKey;
    }

    setSortKey(sortKey, default_value) {
        this._sortKey = this.setValue(this._sortKey, sortKey, default_value);
    }

    set sortKey(sortKey) {
        this._sortKey = sortKey;
    }

    get sortType() {
        return this._sortType;
    }

    set sortType(sortType) {
        this._sortType = sortType;
    }

    setSortType(sortType, default_value) {
        this._sortType = this.setValue(this._sortType, sortType, default_value);
    }

    setValue(property, value, default_value) {
        return value == null ? property == null ? default_value : property : value;
    }

}

class SearchPageable extends Pageable {

    toParams(params, full = true) {
        params = params ? params : '';

        params += this.createParam("search");

        return super.toParams(params, full);
    }

    fromUri() {
        let uri = super.fromUri();

        this.search = uri.searchParams.get('search');
    }


    get search() {
        return this._search;
    }

    set search(search) {
        this._search = search === undefined ? this._search : search;
    }

}

class SortKeyTypes {
    static ASC = 'asc';
    static DESC = 'desc';
}

class JsonHelper {

    static isJson(json) {
        try {
            JSON.parse(json);
        } catch (e) {
            return false;
        }
        return true;
    }

}

class RequestHelper {

    static getUriParams() {
        return new URLSearchParams(document.location.search.substring(1));
    }

    static getUriParam(param) {
        return this.getUriParams().get(param);
    }

}
