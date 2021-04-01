<div class="user small card">
    <img src="" class="card-img-top d-none user-image" alt="user-image">
    <div class="card-body">
        <h5 class="card-title">{{pseudo}} <a href="mailto:{{email}}" class="primary" title="Envoyer un mail"><i class="fas fa-envelope"></i></a></h5>
        <p class="card-text">
            Role: {{role_name}}
            <a href="/user/role/edit?uuid={{uuid}}" class="primary user-edit-role d-none">Modifier le rôle</a>
        </p>
    </div>
    <div class="card-footer">
        <small class="text-muted">Inscription: {{registration_date}}</small>
    </div>
    <div class="d-none user-uuid">{{uuid}}</div>
</div>
