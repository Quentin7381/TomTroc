<?php
use View\Component;

$attributes->add('class', 'padding-container');
?>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        activeLink('<?= $activeLink ?>');
    });
</script>

<header <?= $attributes ?>>
    <?= $v->image_get('logo')->addAttributes('class', 'logo') ?>
    <?= Component::menuMain() ?>
    <?= Component::menuUser() ?>
    <?= Component::menuBurger() ?>
</header>
