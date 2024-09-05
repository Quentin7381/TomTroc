<?php
use View\Component;
?>

<main <?=$attributes?>>
    <?=Component::contacts(['user' => $user, 'attributes' => ['class' => $bem->e('contacts')]])?>
    <?=Component::messages(['user' => $user, 'attributes' => ['class' => $bem->e('messages')]])?>
</main>