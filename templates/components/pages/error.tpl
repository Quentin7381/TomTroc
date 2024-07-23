<main <?=$attributes?>>
    <h1>
        <?php

        if($code == 404) {
            echo 'Page not found';
        } elseif($code == 403) {
            echo 'Forbidden';
        } elseif($code == 422) {
            echo 'Invalid data';
        } elseif($code == 500) {
            echo 'Internal server error';
        } else {
            echo 'Unknown error';
        }
        ?>
    </h1>
</main>
