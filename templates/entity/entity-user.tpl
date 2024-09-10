<section <?= $attributes ?>>
    <?= $entity->photo->render(['attributes' => ['class' => $bem->e('photo')]]) ?>
    <span class="<?= $bem->e('line') ?>"></span>
    <h1 class="<?= $bem->e('name') ?>"><?= $entity->name ?></h1>
    <p class="<?= $bem->e('created') ?>">Membre depuis <?= $entity->account_age ?></p>
    <h4>Bibliotheque</h4>
    <p class="<?= $bem->e('books') ?>">
        <?= $v->image_get('ico-book')->addAttributes('class', $bem->e('bookIcon')) ?><?= $entity->library_size ?>
        livre<?= $entity->library_size > 1 ? 's' : '' ?>
    </p>
    <?php if($v->user_connected) : ?>
        <a href="/message/new/<?=$v->user_current->id?>/<?=$entity->id?>" class="button inverted <?=$bem->e('message')?>">Ecrire un message</a>
    <?php endif ?>
</section>