<?php
use View\Component;

$id = 'burgerMenu';
$attributes->add('data-id', $id);
$attributes->add('class', '--hidden');
?>

<?= Component::buttonBurger([
    'attributes' => [
        'class' => [$bem->e('burger'), 'js-burgerToggler'],
        'data-target' => $id
    ]
]) ?>

<nav <?= $attributes ?>>
    <?= $v->image_get('logo-small')->addAttributes('class', 'logo_small', $bem->e('logo')) ?>
    <?= Component::buttonCross([
        'attributes' => [
            'class' => [$bem->e('cross'), 'js-burgerToggler'],
            'data-target' => $id
        ]
    ]) ?>
    <?= Component::menuMain(['attributes' => ['class' => $bem->e('subMenu')]]) ?>
    <?= Component::menuUser(['attributes' => ['class' => $bem->e('subMenu')]]) ?>
</nav>
