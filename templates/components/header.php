<?php

use Entity\Component;
use Entity\Image;

$logo = new Image();
$logo->name = 'logo';
$logo->extension = 'svg';

?>

<ul>
    <li>
        <?= $logo->render() ?>
    </li>
    <li>
        <?= Component::menu() ?>
    </li>
    <li>
        <?= Component::menuUser() ?>
    </li>
</ul>
