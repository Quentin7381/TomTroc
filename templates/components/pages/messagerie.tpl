<?php
use View\Component;

if (empty($selectedId)) {
    @$selected = $selected ?? reset($v->message_contacts($user));
    $selectedId = $selected->id;
} else {
    $selected = $newContact ?? $v->message_contacts($user)[$selectedId];
}
?>

<main <?= $attributes ?>>
    <section class="<?= $bem->e('contacts') ?> <?=$phoneSelected ? '--hidden' : '' ?>">
        <h2>Messagerie</h2>
        <?php foreach ($v->message_contacts($user) as $contact): ?>
            <?= $contact->render(['user' => $user, 'attributes' => ['class' => $selectedId === $contact->id ? 'selected' : '']], 'contact') ?>
        <?php endforeach; ?>
        <?php if (isset($newContact)): ?>
            <?= $newContact->render(['user' => $user, 'attributes' => ['class' => 'selected']], 'contact') ?>
        <?php endif; ?>
    </section>
    <?= Component::messages(
        [
            'user' => $user,
            'contact' => $selected,
            'attributes' => [
                'class' => [
                    $bem->e('messages'),
                    $phoneSelected ? '--selected' : ''
                ]
            ]
        ]
    ) ?>
</main>