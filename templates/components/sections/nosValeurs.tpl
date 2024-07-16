<section <?= $attributes ?>>
    <div class="<?= $bem->e('subContainer') ?>">
        <h2>Nos valeurs</h2>
        <p>Chez Tom Troc, nous mettons l'accent sur le partage, la découverte et la communauté. Nos valeurs sont ancrées
            dans notre passion pour les livres et notre désir de créer des liens entre les lecteurs. Nous croyons en la
            puissance des histoires pour rassembler les gens et inspirer des conversations enrichissantes.</p>
        <p>Notre association a été fondée avec une conviction profonde : chaque livre mérite d'être lu et partagé.</p>
        <p>Nous sommes passionnés par la création d'une plateforme conviviale qui permet aux lecteurs de se connecter,
            de partager leurs découvertes littéraires et d'échanger des livres qui attendent patiemment sur les
            étagères.</p>
        </br>
        <p class="credit <?= $bem->e('credit') ?>">L'équipe Tom Troc</p>
        <?= $v->image_get('heart')->addAttributes('class', $bem->e('heart')) ?>
    </div>
</section>