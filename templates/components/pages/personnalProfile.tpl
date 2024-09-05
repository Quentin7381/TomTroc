<?php
    use View\Component;
    $style = $editable ? 'editable' : '';
    $attributes->add('class', 'padding-container');
?>

<main <?=$attributes?>>
    <h1>Mon compte</h1>
    <?= $v->user_current->render([], $style) ?>
    <?= Component::informationsPersonnelles() ?>
    <?= Component::userBooks(['books' => $v->user_current->books, 'editable' => $editable]) ?>
</main>
