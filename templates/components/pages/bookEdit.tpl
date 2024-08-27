<?php
    $isAdding = isset($book) ? false : true;
    $title = $book->title ?? '';
    $author = $book->author ?? '';
    $description = $book->description ?? '';
    $available = $book->available ?? '';
    $photo = $book->photo ?? null;
    $action = $isAdding ? 'book/add/' : 'book/edit/' . $book->id;
?>

<main <?=$attributes?>>
    <p class="<?=$bem->e('back')?>"><a href="">← retour</a></p>
    <h1><?= $isAdding ? "Ajouter un livre" : "Modifier les informations"?></h1>
    <section>
        <form action="<?=$action?>">
            <fieldset>
                <label for="photo">Photo</label>
                <?=$photo?>
                <?php if(!$photo) : ?>
                    <a href='' class='button inverted thin <?=$bem->e('addPhoto')?>'>Ajouter une photo</a>
                <?php endif?>
                <input type="file" name="photo" id="photo" accept="image/*" hidden required>
            </fieldset>
            <fieldset>
                <label for="title">Titre</label>
                <input type="text" name="title" id="title" value="<?=$title?>" required>
                <label for="author">Auteur</label>
                <input type="text" name="author" id="author" value="<?=$author?>" required>
                <label for="description">Commentaire</label>
                <textarea name="description" id="description" required><?=$description?></textarea>
                <label for="available">Disponibilité</label>
                <select name="available" id="available" required>
                    <option value="1" <?=$available ? "selected" : ""?>>Disponible</option>
                    <option value="0" <?=$available ? "" : "selected"?>>Indisponible</option>
                </select>
                <button type="submit">Valider</button>
            </fieldset>
        </form>
    </section>
</main>
