<?php
use View\Component;
?>

<section <?=$attributes?>>
    <?=$entity->photo->render(['attributes' => ['class' => $bem->e('photo')]])?>

    <a href="" data-toggle="coverPopup">modifier</a>
    <span class="<?=$bem->e('line')?>"></span>
    <h1 class="<?=$bem->e('name')?>"><?=$entity->name?></h1>
    <p class="<?=$bem->e('created')?>">Membre depuis <?=$entity->account_age?></p>
    <h4>Bibliotheque</h4>
    <p class="<?=$bem->e('books')?>"><?=$v->image_get('ico-book')->addAttributes('class', $bem->e('bookIcon'))?><?=$entity->library_size?> livre<?=$entity->library_size > 1 ? 's' : ''?></p>
    <?=Component::coverPopup()?>
</section>
