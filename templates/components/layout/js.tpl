<?php

use View\View;

$js = View::getInstance()->js;

foreach ($js as $file => $value) {
    echo '<script src="/' . $file . '"></script>';
}
