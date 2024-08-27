<?php
    $router = Router\Router::getInstance();
    $route = $router->getCalledRoute();
    $boldMessages = $route === "user/messages" ? "bold" : "";
    $boldUser = in_array($route, ["user", "user/profile"]) ? "bold" : "";
    $boldConnect = $route === "user/connect" ? "bold" : "";
?>

<nav <?= $attributes ?>>
    <a href="/user/messages" class="<?=$boldMessages?>"><?= $v->image_get('ico-message') ?>Messagerie<span
            class="<?= $bem->e('msgCount') ?>"><?= $v->message_count ?></span></a>
    <a href="/user" class="<?=$boldUser?>"><?= $v->image_get('ico-message') ?>Mon compte</a>
    
    <?php if ($v->user_connected) : ?>
        <a href="/user/disconnect">Déconnexion</a>
    <?php else : ?>
        <a href="/user/connect" class="<?=$boldConnect?>">Connexion</a>
    <?php endif; ?>
</nav>