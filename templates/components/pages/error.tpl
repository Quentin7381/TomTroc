<main <?= $attributes ?>>
    <h1>
        <?php
        if ($code === 404) {
            echo 'Page not found';
        } elseif ((int) $code === 403) {
            echo 'Forbidden';
        } elseif ((int) $code === 422) {
            echo 'Invalid data';
        } elseif ((int) $code === 500) {
            echo 'Internal server error';
        } else {
            echo 'Unknown error';
        }
        ?>
    </h1>
    <p>
        <?= $message ?>
    </p>
</main>