<section <?= $attributes ?>>
    <?= $contact->render([], 'mini') ?>
    <?php foreach ($v->message_thread($user, $contact) as $message): ?>
        <?php
        $class = $message->sender->id == $v->user_current->id ? 'sent' : 'received';
        ?>
        <?= $message->render(['attributes' => ['class' => $class]]) ?>
    <?php endforeach; ?>
    <form action="message/send/<?= $user->id ?>/<?= $contact->id ?>" method="post">
        <input type="text" name="content" placeholder="Message" required>
        <button type="submit" class="thin">Envoyer</button>
    </form>
</section>