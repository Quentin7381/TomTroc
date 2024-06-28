<?php
use Utils\Bem;
use Entity\Book;
use Entity\User;
use Entity\Image;

$bem = Bem::I($templateName);

$user = new User();
$user->name = "Jean";

$cover = new Image();
$cover->src = "assets/img/the-kinfolk.png";
$cover->alt = "Couverture du livre";

$book = new Book();
$book->title = "Le livre de la jungle";
$book->author = "Rudyard Kipling";
$book->cover = $cover;
$book->seller = $user;

$lastBooks = [];
for ($i = 0; $i < 4; $i++) {
    $lastBooks[] = $book;
}
?>

<section <?= Utils\View::renderAttributes($attributes) ?>>
    <h2>Les derniers livres ajoutés</h2>
    <div class="<?= $bem->e('books') ?>">
        <?php foreach ($lastBooks as $book): ?>
            <?= $book->render([], 'card') ?>
        <?php endforeach; ?>
    </div>
    <button class="<?= $bem->e('cta') ?>">Voir tous les livres</button>
</section>
