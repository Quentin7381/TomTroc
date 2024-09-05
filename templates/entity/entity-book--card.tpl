
    <article  <?= $attributes ?>>
        <a href="<?= '/book/' . $entity->id ?>" target="_blank"><?= $entity->cover->render(['attributes' => ['class' => $bem->e('cover')]]) ?></a>
        <a href="<?= '/book/' . $entity->id ?>" target="_blank"><h3 class="<?= $bem->e('title') ?>"><?= $entity->title ?></h3></a>
        <a href="<?= '/book/' . $entity->id ?>" target="_blank"><p class="<?= $bem->e('author') ?>"><?= $entity->author ?></p></a>
        <a class="<?= $bem->e('seller') ?>" href="<?= '/user/' . $entity->seller->id ?>">Vendu par <?= $entity->seller->name ?></a>
    </article>
