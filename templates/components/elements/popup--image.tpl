<?php
    $attributes->add('class', '--hidden');
    $view = View\View::getInstance();
    $view->addJs('imagePreview');
    $previewId = uniqid('preview-');
?>

<div <?= $attributes ?> data-popup="<?=$popupId?>">
    <div class="<?= $bem->e('shadow') ?>"></div>
    <form action="<?=$action?>" method="post" enctype="multipart/form-data" class="<?=$bem->e('box')?>">
        <input type="file" id="file-upload" name="photo" class="<?= $bem->e('fileInput') ?>" required data-image-preview-input="<?=$previewId?>">
        <img class="<?= $bem->e('preview')?> --hidden" data-image-preview="<?=$previewId?>"></img>
        <div class="<?= $bem->e('action') ?>">
            <a class="<?= $bem->e('cancelButton') ?> button error" data-popup-toggler="<?=$popupId?>">Annuler</a>
            <button type="submit" class="<?= $bem->e('submitButton') ?>">Confirmer</button>
        </div>
    </form>
</div>
