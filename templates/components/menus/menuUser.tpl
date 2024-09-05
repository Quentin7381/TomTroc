<?php
    $router = Router\Router::getInstance();
    $route = $router->getCalledRoute();
    $boldMessages = $route === "user/messages" ? "bold" : "";
    $boldUser = in_array($route, ["user", "user/profile"]) ? "bold" : "";
    $boldConnect = $route === "user/connect" ? "bold" : "";
?>

<nav <?= $attributes ?>>
    <a href="/messagerie" class="<?=$boldMessages?>"><?= $v->image_get('ico-message') ?>Messagerie
        <?php if ($v->message_count > 0) : ?>
            <span class="<?= $bem->e('msgCount') ?>"><?= $v->message_count ?></span>
        <?php endif; ?>
    </a>
    <a href="/user" class="<?=$boldUser?>"><?= $v->image_get('ico-message') ?>Mon compte</a>
    
    <?php if ($v->user_connected) : ?>
        <a href="/user/disconnect">Déconnexion</a>
    <?php else : ?>
        <a href="/user/connect" class="<?=$boldConnect?>">Connexion</a>
    <?php endif; ?>
</nav>