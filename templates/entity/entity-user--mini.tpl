<a <?=$attributes?> href="/user/<?=$entity->id?>">
    <?=$entity->photo->render([], 'mini')?>
    <p class="<?= $bem->e('name')?>">
        <?=$entity->name?>
    </p>
</a>