<article <?= Utils\View::renderAttributes($attributes) ?>>
    <?= $entity->cover->render(['attributes' => ['class' => $bem->e('cover')]]) ?>
    <h3 class="<?= $bem->e('title') ?>"><?= $entity->title ?></h3>
    <p class="<?= $bem->e('author') ?>"><?= $entity->author ?></p>
    <p class="<?= $bem->e('seller') ?>">Vendu par <?= $entity->seller->name ?></p>
</article>
