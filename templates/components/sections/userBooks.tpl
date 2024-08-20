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
                <th>Disponibilité</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book) {
                echo $book->render([], 'row');
            }
            
            if (empty($books)) {
                echo '<tr><td colspan="6">Aucun livre</td></tr>';
            }
            ?>
        </tbody>
    </table>
</section>
