<section <?=$attributes?>>
    <?=$contact->render([], 'mini')?>
    <?php foreach ($v->message_thread($user, $contact) as $message): ?>
        <?=$message?>
    <?php endforeach; ?>
</section>