<?php
use Utils\Bem;
use Entity\Book;
use Entity\User;
use Entity\Image;
use Variables\Variables;

$bem = Bem::I($templateName);
?>

<section <?= Utils\View::renderAttributes($attributes) ?>>
    <h2>Les derniers livres ajoutés</h2>
    <div class="<?= $bem->e('books') ?>">
        <?php foreach ($v->book_lasts->getNexts(5) as $book): ?>
            <?= $book->render([], 'card') ?>
        <?php endforeach; ?>
    </div>
    <button class="<?= $bem->e('cta') ?>">Voir tous les livres</button>
</section>
