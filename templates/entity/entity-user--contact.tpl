<?php
$messages = $v->message_thread($user, $entity);
$message = $messages->last();
?>

<article <?= $attributes ?>>
    <a href="/messagerie?id=<?= $entity->id ?>">
        <?= $entity->photo->render(['attributes' => ['class' => $bem->e('photo')]], 'mini') ?>
        <div class="<?= $bem->e('infos') ?>">
            <p class="<?= $bem->e('header') ?>">
                <span class="<?= $bem->e('name') ?>"><?= $entity->name ?></span>
                <span class="<?= $bem->e('hour') ?>"><?= $message->hour ?? null ?></span>
            </p>
            <p class="<?= $bem->e('content') ?>"><?= $message->content ?? null ?></p>
        </div>
    </a>
</article>
