<section <?= $attributes ?>>
    <?= $contact->render([], 'mini') ?>
    <?php foreach ($v->message_thread($user, $contact) as $message): ?>
        <?php
        $class = $message->sender->id == $v->user_current->id ? 'sent' : 'received';
        ?>
        <?= $message->render(['attributes' => ['class' => $class]]) ?>
    <?php endforeach; ?>
</section>  