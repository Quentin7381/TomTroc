<?php
use Entity\Component;
?>

<header <?= Utils\View::renderAttributes($attributes)?>>
    <?= $v->image_get('logo')->addAttribute('class', 'logo')->render() ?>
    <?= Component::menuMain() ?>
    <?= Component::menuUser() ?>
</header>
