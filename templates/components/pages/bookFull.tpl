<div <?= $attributes ?>>
    <section class="<?= $bem->e('breadcrumb') ?> padding-container">
        <p>
            <span><a href="">Nos livres</a></span>
            <span><a href=""><?= $book->title ?></a></span>
        </p>
    </section>
    <main>
        <section class="<?=$bem->e('book')?>">
            <?= $book ?>
        </section>
    </main>
</div>