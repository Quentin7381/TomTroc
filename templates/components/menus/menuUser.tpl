<nav <?= $attributes ?>>
    <a href="/messagerie"><?= $v->image_get('ico-message') ?>Messagerie
        <?php if ($v->message_count > 0) : ?>
            <span class="<?= $bem->e('msgCount') ?>"><?= $v->message_count ?></span>
        <?php endif; ?>
    </a>
    <a href="/user"><?= $v->image_get('ico-account') ?>Mon compte</a>
    
    <?php if ($v->user_connected) : ?>
        <a href="/user/disconnect">Déconnexion</a>
    <?php else : ?>
        <a href="/user/connect">Connexion</a>
    <?php endif; ?>
</nav>