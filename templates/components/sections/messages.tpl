<?php
use View\Component;
?>

<section <?= $attributes ?>>
    <a class="button textOnly <?= $bem->e('back') ?>" href="/messagerie">
        ← Retour
    </a>
    <?= $contact->render([], 'mini') ?>

    <div class="<?= $bem->e('thread') ?>">
        <?php foreach ($v->message_thread($user, $contact) as $message): ?>
            <?php
            $class = (int) $message->sender->id === (int) $v->user_current->id ? 'sent' : 'received';
            ?>
            <?= $message->render(['attributes' => ['class' => $class]]) ?>
        <?php endforeach; ?>
    </div>

    <form action="message/send/<?= $user->id ?>/<?= $contact->id ?>" method="post">
        <input type="text" name="content" placeholder="Tapez votre message ici" required>
        <button type="submit" class="thin">Envoyer</button>
    </form>
</section>