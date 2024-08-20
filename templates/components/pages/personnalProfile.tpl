<?php
use View\Component;
?>

<h1>Mon compte</h1>
<?= $v->user_current->render([], 'editable') ?>
<?= Component::informationsPersonnelles() ?>
