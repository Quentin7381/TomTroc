<section <?=$attributes?>>
    <h2>Messagerie</h2>
    <?php foreach ($v->message_contacts($user) as $contact): ?>
        <?= $contact->render(['user' => $user], 'contact') ?>
    <?php endforeach; ?>
</section>