<?php
    $srcset = $attributes->getAttribute('src')[0];
    $attributes->remove('src');
    $attributes->remove('alt');
?>

<source <?= $attributes ?> srcset="<?= $srcset ?>" />
