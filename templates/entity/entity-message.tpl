<div <?=$attributes?>>
    <h4 class="<?=$bem->e('header')?>">
        <?=$entity->sender->photo->render(['attributes' => ['class' => $bem->e('photo')]], 'mini')?>
        <span class="<?=$bem->e('date')?>"><?=$entity->full_date?></span>
    </h4>
    <p class="<?=$bem->e('content')?>"><?=$entity->content?></p>
</div>