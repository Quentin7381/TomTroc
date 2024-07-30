<?= var_dump($entity->photo) ?>

<article>
    <?=$entity->photo->render()?>
    <h1><?=$entity->name?></h1>
    <p>Membre depuis <?=$entity->account_age?></p>
</article>