<div class="article normal">
    <h1>{{title}}</h1>

    <div class="d-flex mb-1">
        <div class="flex-grow-1">
            <img src="" class="d-none article-user-image rounded-circle user-avatar small" alt="user-image"> {{user_pseudo}}
        </div>
        <div class="me-3">
            {{last_update}}
        </div>
        <div>
            <a class="btn btn-primary edit-article d-none" href="/article/edit?uuid={{uuid}}" role="button"><i class="fas fa-edit"></i></a>
            <button class="btn btn-primary delete-article d-none" role="button"><i class="fas fa-trash"></i></button>
        </div>
    </div>

    <div>
        <img src="" class="card-img-top d-none article-image" alt="article-image">
    </div>

    <div class="mt-3">
        <div>
            {{body}}
        </div>
    </div>

    <div class="d-none article-uuid">{{uuid}}</div>
</div>
