<section <?= $attributes ?>>
    <div class="<?= $bem->e('text') ?> container-xs">
        <h2>Rejoignez nos lecteurs passionnés</h2>
        <p>Donnez une nouvelle vie à vos livres en les échangeant avec d'autres amoureux de la lecture. Nous croyons en
            la magie du partage de connaissances et d'histoires à travers les livres.</p>
        <button class="<?= $bem->e('cta') ?>">Découvrir</button>
    </div>
    <div class="container-xs">
        <?= $v->image_get('hamza-nouasria')->addAttributes('class', $bem->e('image')) ?>
        <p class="credit">Hamza</p>
    </div>
</section>