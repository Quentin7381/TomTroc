<?php
    use Entity\Image;
    $ico_message = new Image();
    $ico_message->name = 'ico_message';
    $ico_message->extension = 'svg';
    $ico_message->alt = 'Messagerie';

    $ico_account = new Image();
    $ico_account->name = 'ico_account';
    $ico_account->extension = 'svg';
    $ico_account->alt = 'Mon compte';
?>

<nav <?= Utils\View::renderAttributes($attributes) ?>>
    <a href=""><?=$ico_message->render()?>Messagerie<span class="msgCount">1</span></a>
    <a href=""><?=$ico_account->render()?>Mon compte</a>
    <a href="">Connexion</a>
</nav>
