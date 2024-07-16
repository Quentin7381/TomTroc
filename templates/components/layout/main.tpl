<?php
use View\Component;

$content = new Component();
$content->template = 'accueil';
foreach ($attributes as $key => $value) {
    $content->addAttribute($key, ...$value);
}
?>

<?= $content ?>