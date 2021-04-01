<form class="article form">
    <input type="text" name="uuid" value="{{uuid}}" class="d-none" />
    <input type="text" name="version" value="{{version_iso}}" class="d-none" />
    <input type="text" name="image_extension" value="{{image_extension}}" class="d-none" />

    <div class="form-group">
        <label for="title">Titre*</label>
        <input type="text" name="title" value="{{title}}" class="form-control" id="title" placeholder="Titre" aria-describedby="validation-title"  required>
        <div id="validation-title" class="invalid-feedback"></div>
    </div>

    <div class="form-group mt-3">
        <label for="excerpt">Résumé de l'article</label>
        <input type="text" name="excerpt" value="{{excerpt}}" class="form-control" id="excerpt" placeholder="Résumé" aria-describedby="validation-excerpt">
        <div id="validation-excerpt" class="invalid-feedback"></div>
    </div>

    <div class="form-group mt-3">
        <label for="excerpt">Image de l'article</label>
        <div class="d-flex">
            <div class="flex-grow-1">
                <input type="file" id="image" name="image" class="form-control" accept="image/png, image/jpeg, image/jpg, image/gif" aria-describedby="validation-image">
                <div id="validation-image" class="invalid-feedback"></div>
            </div>
            <div class="mt-1 pr-1 flex-grow-1 d-flex justify-content-end">
                <div>
                    <img src="" class="card-img-top d-none article-image preview" alt="article-image">
                    <div><i>Image actuelle</i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-3">
        <label for="body">Contenu*</label>
        <textarea class="form-control" id="body" name="body" rows="3" aria-describedby="validation-body" required>{{body}}</textarea>
        <div id="validation-body" class="invalid-feedback"></div>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Publier</button>
</form>
