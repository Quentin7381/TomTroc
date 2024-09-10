<?php
// Style is done for userConnect, we can reuse it
$bem->changeTemplate('userConnect');
$attributes->remove('class', 'tpl-userRegister');
$attributes->add('class', 'tpl-userConnect', 'cols-2');

$view = View\View::getInstance();
$view->addCss('userConnect');
?>

<main <?= $attributes ?>>
    <section class="<?= $bem->e('form') ?>">
        <h1>Inscription</h1>
        <form action="/user/request/register" method="post">
            <label for="name">Pseudo</label>
            <input type="text" name="name" id="name" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">S'inscrire</button>
        </form>
        <p class="<?= $bem->e('cta') ?>">Déjà un inscrit ? <a href='/user/connect'>Connectez-vous</a></p>
    </section>
    <section class="<?= $bem->e('image') ?>">
        <?= $v->image_get('login') ?>
    </section>
</main>