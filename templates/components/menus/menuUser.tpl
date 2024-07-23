<nav <?= $attributes ?>>
    <a href="/user/messages"><?= $v->image_get('ico-message') ?>Messagerie<span
            class="<?= $bem->e('msgCount') ?>"><?= $v->message_count ?></span></a>
    <a href="/user"><?= $v->image_get('ico-message') ?>Mon compte</a>
    
    <?php if ($v->user_connected) : ?>
        <a href="/user/disconnect">Déconnexion</a>
    <?php else : ?>
        <a href="/user/connect">Connexion</a>
    <?php endif; ?>
</nav>