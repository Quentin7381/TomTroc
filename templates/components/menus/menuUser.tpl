<nav <?= $attributes ?>>
    <a href=""><?= $v->image_get('ico-message') ?>Messagerie<span
            class="<?= $bem->e('msgCount') ?>"><?= $v->message_count ?></span></a>
    <a href=""><?= $v->image_get('ico-message') ?>Mon compte</a>
    <a href="">Connexion</a>
</nav>