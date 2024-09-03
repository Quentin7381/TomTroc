<main <?= $attributes ?>>
    <section>
        <div class="<?= $bem->e('titleHold') ?>">
            <h1>Nos livres à l'échange</h1>
            <input type="search" placeholder="Rechercher un livre" class="<?= $bem->e('search')?> search">
        </div>
        <div class="<?= $bem->e('list') ?>">
            <?php foreach ($v->book_lasts as $book): ?>
                <?= $book->render([], 'card') ?>
            <?php endforeach; ?>
        </div>
    </section>
</main>