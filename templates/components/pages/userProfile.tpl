<?php
use View\Component;
?>

<main <?=$attributes?>>
    <?=$user->render(['attributes' => ['class' => $bem->e('user')]])?>
    <?=Component::userBooks(['books' => $user->get_books(), 'editable' => false, 'attributes' => ['class' => $bem->e('books')]])?>
</main>
