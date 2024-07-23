<main <?=$attributes?>>
    <h1>
        <?php

        if($code == 404) {
            echo 'Page not found';
        } else {
            echo 'Unknown error';
        }
        ?>
    </h1>
</main>
