<?php
use View\Component;

$attributes->add('class', 'alternate-bg');
?>

<main <?= $attributes ?>>
    <?= Component::decouvrir() ?>
    <?= Component::derniersLivres() ?>
    <?= Component::commentCaMarche() ?>
    <?= Component::banner() ?>
    <?= Component::nosValeurs() ?>
</main>