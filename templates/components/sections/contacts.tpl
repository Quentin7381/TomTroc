<section <?=$attributes?>>
    <h2>Messagerie</h2>
    <?php foreach ($v->message_contacts($user) as $contact): ?>
        <?= $contact->render([], 'contact') ?>
    <?php endforeach; ?>
</section>