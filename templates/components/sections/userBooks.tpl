<?php

?>

<section <?=$attributes?>>
    <table>
        <thead>
            <tr>
                <th>Photo</th>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Description</th>
                <?php if($editable): ?>
                <th>Disponibilité</th>
                <th>Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book) {
                echo $book->render(['editable' => $editable], 'row');
            }
            
            if (empty($books)) {
                echo '<tr><td colspan="6">Aucun livre</td></tr>';
            }

            if($editable) : ?>
                <tr><td colspan="6"><a href="/book/add" class="button thin <?=$bem->e('add')?>">Ajouter un livre</a></td></tr>
            <?php endif;
            ?>
        </tbody>
    </table>
</section>
