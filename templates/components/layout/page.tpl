<?php
$title = 'TOM Troc' . (isset($title) ? ' | ' . $title : '');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Playfair+Display:wght@400;700&display=swap"
        rel="stylesheet">
</head>

<body>
    <?= View\Component::header(['activeLink' => $activeLink]) ?>
    <?= $body ?>
    <?= View\Component::footer() ?>
</body>

</html>