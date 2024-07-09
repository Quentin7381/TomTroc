<?php
use Entity\Image;

$image = new Image();
$image->src = 'assets/img/library-wide.png';

?>

<section <?=Utils\View::renderAttributes($attributes) ?>>
    <?= $image->render() ?>
</section>
