class UserManager {
    static VIEW_ARTICLE = 0;
    static ADD_ARTICLE = 1;
    static EDIT_OWN_ARTICLE = 2;
    static EDIT_ALL_ARTICLE = 3;
    static REMOVE_OWN_ARTICLE = 4;
    static REMOVE_ALL_ARTICLE = 5;
    static CHANGE_ROLE = 6;


    static hasPermission(permission) {
        return this.finUserValue('permission-' + permission) != null;
    }

    static isOwner(userId) {
        return String(this.finUserValue('uuid')) === String(userId);
    }

    static finUserValue(val) {
        let container = document.querySelector('.user-session-' + val);
        return container ? container.textContent : null;
    }
}
