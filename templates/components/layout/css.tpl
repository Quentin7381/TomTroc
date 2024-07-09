<?php

use View\View;

$css = View::getInstance()->css;

foreach ($css as $style => $value) {
    echo '<link rel="stylesheet" type="text/css" href="' . $style . '">';
}
