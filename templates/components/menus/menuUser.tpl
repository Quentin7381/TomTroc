<?php
    use Entity\Image;
    $ico_message = new Image();
    $ico_message->src = 'assets/img/ico_message.svg';
    $ico_message->alt = 'Messagerie';

    $ico_account = new Image();
    $ico_account->src = 'assets/img/ico_account.svg';
    $ico_account->alt = 'Mon compte';
?>

<nav <?= Utils\View::renderAttributes($attributes) ?>>
    <a href=""><?=$ico_message->render()?>Messagerie<span class="<?=$bem->e('msgCount')?>"><?=$v->message_count?></span></a>
    <a href=""><?=$ico_account->render()?>Mon compte</a>
    <a href="">Connexion</a>
</nav>
