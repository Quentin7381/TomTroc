<?php
    $attributes->add('class', '--hidden');
?>

<div <?= $attributes ?>>
    <div class="<?= $bem->e('shadow') ?>"></div>
    <form action="/user/update/photo" method="post" enctype="multipart/form-data" class="<?=$bem->e('box')?>">
        <input type="file" id="file-upload" name="photo" class="<?= $bem->e('fileInput') ?>" required>
        <img class="<?= $bem->e('preview')?> --hidden"></img>
        <div class="<?= $bem->e('action') ?>">
            <button class="<?= $bem->e('cancelButton') ?> error" data-toggle="coverPopup">Annuler</button>
            <button type="submit" class="<?= $bem->e('submitButton') ?>">Confirmer</button>
        </div>
    </form>
</div>
