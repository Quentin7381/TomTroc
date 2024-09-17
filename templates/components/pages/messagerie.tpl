<?php
use View\Component;
$selectedId = $selected->id ?? null;
// }
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
    <?php if(!empty($selected)): ?>        
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
    <?php else: ?>
        <section class="<?= $bem->e('messages') ?> --selected">
            <h2>Aucun contact pour le moment</h2>
        </section>
    <?php endif; ?>
</main>
