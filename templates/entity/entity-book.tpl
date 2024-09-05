<article <?=$attributes?>>
    <div class="<?=$bem->e('cover')?>">
        <?=$entity->cover?>
    </div>
    <div class="<?=$bem->e('details')?>">
        <h2 class="<?=$bem->e('title')?>"><?=$entity->title?></h2>
        <p class="<?=$bem->e('author')?> text-light">Par <?=$entity->author?></p>
        <p class="<?=$bem->e('line')?>"></p>
        <h3>Description</h4>
        <p class="<?=$bem->e('description')?>"><?=$entity->description?></p>
        <h3>Propriétaire</h4>
        <?=$entity->seller->render([], 'mini')?>
    </div>
</article>