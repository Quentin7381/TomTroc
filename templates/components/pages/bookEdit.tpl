<?php
$isAdding = isset($book) ? false : true;
$title = $book->title ?? '';
$author = $book->author ?? '';
$description = $book->description ?? '';
$available = $book->available ?? '';
$photo = $book->cover ?? null;
$action = $isAdding ? '/book/add/submit' : '/book/edit/' . $book->id . '/submit';

$previewId = uniqid('preview-');
$view = View\View::getInstance();
$view->addJs('imagePreview');
?>

<main <?= $attributes ?>>
    <p class="<?= $bem->e('back') ?>"><a href="">← retour</a></p>
    <h1><?= $isAdding ? "Ajouter un livre" : "Modifier les informations" ?></h1>
    <section>
        <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <p class="label">Photo</p>
                <?php if ($photo): ?>
                    <?php $photo->addAttributes('class', 'bookCover'); ?>
                    <?= $photo ?>
                <?php else: ?>
                    <img src="" alt="" data-image-preview="<?= $previewId ?>"
                        class="<?= $bem->e('preview') ?> bookCover --hidden">
                <?php endif ?>
                <label for="photo" class="<?= $bem->e("addPhoto") ?>">
                    <?=
                        $photo ?
                        "Modifier la photo" :
                        "Ajouter une photo"
                        ?>
                </label>
                <input type="file" name="photo" id="photo" hidden data-image-preview-input="<?= $previewId ?>" <?= $isAdding ? "required" : "" ?>>
            </fieldset>
            <fieldset>
                <label for="title">Titre</label>
                <input type="text" name="title" id="title" value="<?= $title ?>" required>
                <label for="author">Auteur</label>
                <input type="text" name="author" id="author" value="<?= $author ?>" required>
                <label for="description">Commentaire</label>
                <textarea name="description" id="description" required><?= $description ?></textarea>
                <label for="available">Disponibilité</label>
                <select name="available" id="available" required>
                    <option value="1" <?= $available ? "selected" : "" ?>>Disponible</option>
                    <option value="0" <?= $available ? "" : "selected" ?>>Indisponible</option>
                </select>
                <button type="submit">Valider</button>
            </fieldset>
        </form>
    </section>
</main>