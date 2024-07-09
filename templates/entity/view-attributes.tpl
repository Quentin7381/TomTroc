<?php
foreach ($entity->data as $key => $value) {
    if (is_array($value)) {
        $value = implode(' ', $value);
    }
    echo ' ' . $key . '="' . $value . '"';
}
