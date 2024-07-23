<?php
use View\Component;

$attributes->add('class', 'padding-container');
?>

<header <?= $attributes ?>>
    <?= $v->image_get('logo')->addAttributes('class', 'logo') ?>
    <?= Component::menuMain() ?>
    <?= Component::menuUser() ?>
    <?= Component::menuBurger() ?>
</header>
