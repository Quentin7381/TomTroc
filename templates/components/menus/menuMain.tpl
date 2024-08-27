<?php
    $router = Router\Router::getInstance();
    $route = $router->getCalledRoute();
    $boldAccueil = in_array($route, ["/", ""]) ? "bold" : "";
    $boldLivres = $route === "livres" ? "bold" : "";
?>

<nav <?= $attributes ?>>
    <a href="/" class="<?=$boldAccueil?>">Accueil</a>
    <a href="/livres" class="<?=$boldLivres?>">Nos livres à l'échange</a>
</nav>