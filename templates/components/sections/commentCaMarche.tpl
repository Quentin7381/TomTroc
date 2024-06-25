<?php
use Utils\Bem;
$bem = Bem::I($templateName);

?>

<section <?= Utils\View::renderAttributes($attributes) ?>>
    <div class="container-xs">
        <h2>Comment ça marche ?</h2>
        <p>Échanger des livres avec TomTroc c'est simple et amusant ! Suivez ces étapes pour commencer :</p>
    </div>
    <div class="<?=$bem->e('list')?>">
        <a href="">Inscrivez-vous gratuitement sur notre plateforme.</a>
        <a href="">Ajoutez des livres que vous souhaitez échanger à votre profil.</a>
        <a href="">Parcourez des livres disponnibles chez d'autres membres.</a>
        <a href="">Proposez un échange et discutez avec d'autres passionnés de lecture.</a>
    </div>
    <button class="inverted">Voir tous les livres</button>
</section>