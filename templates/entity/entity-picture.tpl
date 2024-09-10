<picture <?= $attributes ?>>
    <?php
    foreach ($entity->images as $key => $image) {
        if ((int)$key === 0) {
            $image->render([], 'source');
            continue;
        }

        echo $image->render([
            'attributes' => [
                'media' => "(max-width: " . $key . "px)"
            ]
        ], 'source');
    }

    if (isset($entity->images[0])) {
        echo $entity->images[0]->render();
    }
    ?>
</picture>