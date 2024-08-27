<?php
    use View\Component;
    $style = $editable ? 'editable' : '';
?>

<main <?=$attributes?>>
    <h1>Mon compte</h1>
    <?= $v->user_current->render([], $style) ?>
    <?= Component::informationsPersonnelles() ?>
    <?= Component::userBooks(['books' => $v->user_current->books, 'editable' => $editable]) ?>
</main>
