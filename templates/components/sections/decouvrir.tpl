<?php
use Utils\Bem;

$bem = Bem::I($templateName);

$img = new Entity\Image();
$img->src = 'assets/img/hamza-nouasria.png';
$img->alt = 'Hamza nouasria';

$vars = get_defined_vars();
?>

<section <?= Utils\View::renderAttributes($attributes) ?>>
    <div class="<?= $bem->e('text') ?>">
        <h2>Rejoignez nos lecteurs passionnés</h2>
        <p>Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en
            la magie du partage de connaissances et d'histoires à travers les livres.</p>
        <button class="<?= $bem->e('cta') ?>">Découvrir</button>
    </div>
    <div>
        <?= $img->render() ?>
        <p class="credit">Hamza</p>
    </div>
</section>