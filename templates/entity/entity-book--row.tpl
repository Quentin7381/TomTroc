<tr <?= $attributes ?>>
    <td><?= $entity->cover->render(['attributes' => ['class' => $bem->e('cover')]]) ?></td>
    <td class="<?= $bem->e('title') ?>"><?= $entity->title ?></td>
    <td class="<?= $bem->e('author') ?>"><?= $entity->author ?></td>
    <td class="<?= $bem->e('description') ?>"><p><?= $entity->description ?><p></td>
    <td class="<?= $bem->e('available') ?>">
        <?=
            $entity->available ?
            '<span class="available">disponible</span>' :
            '<span class="unavailable">non dispo.</span>'
        ?>
    </td>
    <td class="<?= $bem->e('action') ?>">
        <div class="container">
            <a href="/book/edit/<?=$entity->id?>" class="edit">Éditer</a><a href="/book/delete/<?=$entity->id?>" class="delete">Supprimer</a>
        </div>
    </td>
</tr>
