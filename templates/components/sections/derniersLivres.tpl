<?php
use Utils\Bem;
use Entity\Book;
use Entity\User;
use Entity\Image;

?>

<section <?= $attributes ?>>
    <h2>Les derniers livres ajoutés</h2>
    <div class="<?= $bem->e('books') ?>">
        <?php foreach ($v->book_lasts->getNexts(5) as $book): ?>
            <?= $book->render([], 'card') ?>
        <?php endforeach; ?>
    </div>
    <a href="/book/list" class="<?= $bem->e('cta') ?> button">Voir tous les livres</a>
</section>