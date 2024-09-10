<?php
$attributes->add('class', 'cols-2');
?>

<main <?=$attributes?>>
    <section class="<?=$bem->e('form')?>">
        <h1>Connexion</h1>
        <form action="/user/request/login" method="post">
            <label for="email">Adresse email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Se connecter</button>
        </form>
        <p class="<?=$bem->e('cta')?>">Pas de compte ? <a href='/user/register'>Inscrivez-vous</a></p>
    </section>
    <section class="<?=$bem->e('image')?>">
        <?=$v->image_get('login')?>
    </section>
</main>