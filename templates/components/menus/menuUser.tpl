<nav <?= $attributes->render() ?>>
    <a href=""><?= $v->image_get('ico-message')->render() ?>Messagerie<span
            class="<?= $bem->e('msgCount') ?>"><?= $v->message_count ?></span></a>
    <a href=""><?= $v->image_get('ico-message')->render() ?>Mon compte</a>
    <a href="">Connexion</a>
</nav>