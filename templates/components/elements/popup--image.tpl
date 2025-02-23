<?php
    $attributes->add('class', '--hidden');
    $view = View\View::getInstance();
    $view->addJs('imagePreview');
    $previewId = uniqid('preview-');
?>

<div <?= $attributes ?> data-popup="<?=$popupId?>">
    <div class="<?= $bem->e('shadow') ?>"></div>
    <form action="<?=$action?>" method="post" enctype="multipart/form-data" class="<?=$bem->e('box')?>">
        <input type="file" id="file-upload" name="photo" class="<?= $bem->e('fileInput') ?>" required data-image-preview-input="<?=$previewId?>" accept="image/*">
        <img class="<?= $bem->e('preview')?> --hidden" data-image-preview="<?=$previewId?>" src="https://salonlfc.com/wp-content/uploads/2018/01/image-not-found-1-scaled.png" alt="Image preview">
        <div class="<?= $bem->e('action') ?>">
            <a class="<?= $bem->e('cancelButton') ?> button error" data-popup-toggler="<?=$popupId?>">Annuler</a>
            <button type="submit" class="<?= $bem->e('submitButton') ?>">Confirmer</button>
        </div>
    </form>
</div>
