<?php
use View\Component;

?>

<header <?= $attributes->render() ?>>
    <?= $v->image_get('logo')->addAttributes('class', 'logo')->render() ?>
    <?= Component::menuMain() ?>
    <?= Component::menuUser() ?>
</header>
