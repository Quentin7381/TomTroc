<?php
    use Entity\Image;

    $image = new Image();
    $image->src = 'assets/img/logo_small.svg';
    $image->alt = 'Logo';
    $image->addAttribute('class', 'logo_small');
?>

<footer <?= Utils\View::renderAttributes($attributes)?>>
    <a href="">Politique de confidentialité</a>
    <a href="">Mentions légales</a>
    <a href="">Tom Troc©</a>
    <?= $image->render() ?>
</footer>
