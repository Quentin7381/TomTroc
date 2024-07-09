<?php
use Entity\Component;
$attributes['class'][] = 'alternate-bg';
?>

<main <?= Utils\View::renderAttributes($attributes) ?>>
    <?= Component::decouvrir() ?>
    <?= Component::derniersLivres() ?>
    <?= Component::commentCaMarche() ?>
    <?= Component::banner() ?>
    <?= Component::nosValeurs() ?>
</main>
