<nav <?= $attributes ?>>
    <a href="user/messages"><?= $v->image_get('ico-message') ?>Messagerie<span
            class="<?= $bem->e('msgCount') ?>"><?= $v->message_count ?></span></a>
    <a href="user"><?= $v->image_get('ico-message') ?>Mon compte</a>
    <a href="user/connect">Connexion</a>
</nav>