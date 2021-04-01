class Displayer {
    static BRACKET_PATTERN = new RegExp('\{\{(.*?)\}\}', 'g');

    static getComponentContainer() {
        return document.querySelector('.components');
    }

    static convertComponent(component) {
        if (JsonHelper.isJson(component)) {
            return JSON.parse(component);
        }

        return component;
    }

    static bindComponent(container, component) {
        let toBind = container.innerHTML;

        if(container && component && typeof component === 'object') {
            Object.keys(component).forEach(key => {
                if(component[key]) {
                    toBind = toBind.replaceAll('{{' + key + '}}', component[key]);
                }
            });

            // Replace all not binded
            toBind = toBind.replaceAll(this.BRACKET_PATTERN, '');
        } else {
            // Replace all not binded
            toBind = toBind.replaceAll(this.BRACKET_PATTERN, '');
        }

        container.innerHTML = toBind;
    }

    static displayImage(container, imagePath) {
        if(container && imagePath) {
            //TODO make a route to get resources.
            container.setAttribute("src", '/blog/' + imagePath);
            container.classList.remove('d-none');
        }
    }
}

class ArticleDisplayer extends Displayer {
    static TYPE_SMALL = "small";
    static TYPE_NORMAL = "normal";
    static TYPE_FORM = "form";

    static displayArticle(article, container, type = this.TYPE_NORMAL) {
        article = this.convertComponent(article);

        if(article || type === this.TYPE_FORM) {
            let articleContainer = this.getComponentContainer().querySelector('.article.' + type).cloneNode(true);

            this.bindComponent(articleContainer, article);

            if (article) {
                this.displayImage(articleContainer.querySelector('.article-image'), article.image_path);
                this.displayImage(articleContainer.querySelector('.article-user-image'), article.user_image);

                let editBtn = articleContainer.querySelector('.edit-article');
                let deleteBtn = articleContainer.querySelector('.delete-article');

                if(editBtn &&
                    (UserManager.isOwner(article.id_user) && UserManager.hasPermission(UserManager.EDIT_OWN_ARTICLE)) ||
                    (!UserManager.isOwner(article.id_user) && UserManager.hasPermission(UserManager.EDIT_ALL_ARTICLE))) {
                    DomManager.toggle(editBtn, true);
                }

                if(deleteBtn &&
                    (UserManager.isOwner(article.id_user) && UserManager.hasPermission(UserManager.REMOVE_OWN_ARTICLE)) ||
                    (!UserManager.isOwner(article.id_user) && UserManager.hasPermission(UserManager.REMOVE_ALL_ARTICLE))) {
                    DomManager.toggle(deleteBtn, true);
                }
            }

            container.appendChild(articleContainer);
        }
    }

    static displayArticles(articles, container, type = this.TYPE_SMALL) {
        articles = this.convertComponent(articles);
        if(Array.isArray(articles)) {
            articles.map(article => this.displayArticle(article, container, type));
        }
    }
}

class UserDisplayer extends Displayer {
    static TYPE_SMALL = "small";
    static TYPE_FORM = "form";
    static TYPE_CHANGE_ROLE_FORM = "change-role-form";

    static displayUser(user, container, type = this.TYPE_SMALL) {
        user = this.convertComponent(user);

        if(user || type === this.TYPE_FORM) {
            let userContainer = this.getComponentContainer().querySelector('.user.' + type).cloneNode(true);

            this.bindComponent(userContainer, user);

            if (user) {
                this.displayImage(userContainer.querySelector('.user-user-image'), user.user_image);

                let roleSelect = userContainer.querySelector('#user-role');
                let userEditRoleBtn = userContainer.querySelector('.user-edit-role');

                if(roleSelect) {
                    roleSelect.value = user.id_role;
                }

                if(userEditRoleBtn &&
                    (UserManager.finUserValue('uuid') !== user.uuid && UserManager.hasPermission(UserManager.CHANGE_ROLE))
                    && UserManager.finUserValue('role') >= user.id_role) {
                    DomManager.toggle(userEditRoleBtn, true);
                }
            }

            container.appendChild(userContainer);
        }
    }

    static displayUsers(users, container, type = this.TYPE_SMALL) {
        users = this.convertComponent(users);
        if(Array.isArray(users)) {
            users.map(user => this.displayUser(user, container, type));
        }
    }
}
