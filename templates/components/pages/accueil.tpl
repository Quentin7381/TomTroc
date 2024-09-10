<?php
use View\Component;

$attributes->add('class', 'alternate-bg', 'no-container');
?>

<main <?= $attributes ?>>
    <?= Component::decouvrir() ?>
    <?= Component::derniersLivres() ?>
    <?= Component::commentCaMarche() ?>
    <?= Component::banner() ?>
    <?= Component::nosValeurs() ?>
</main>