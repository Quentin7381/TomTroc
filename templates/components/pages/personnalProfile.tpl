<?php
use View\Component;
?>

<main <?=$attributes?>>
    <h1>Mon compte</h1>
    <?= $v->user_current->render([], 'editable') ?>
    <?= Component::informationsPersonnelles() ?>
    <?= Component::userBooks(['books' => $v->user_current->books]) ?>
</main>
