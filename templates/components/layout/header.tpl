<?php

use Entity\Component;
use Entity\Image;

$logo = new Image();
$logo->name = 'logo';
$logo->extension = 'svg';
$logo->alt = 'TomTroc';
$logo->addAttribute('class', 'logo');

?>

<header <?= Utils\View::renderAttributes($attributes)?>>
    <?= $logo->render() ?>
    <?= Component::menuMain() ?>
    <?= Component::menuUser() ?>
</header>
