<?php

namespace Utils;

use Config\Config;

class View {

    public function render($entity, $data = [], $style = null) {
        $className = get_class($entity);
        $className = str_replace('Entity\\', '', $className);

        $fileName = Config::TEMPLATE_DIR . $className;

        if (!empty($style)) {
            $fileNameStyle = '-' . $style;
            $fileNameStyle .= '.php';

            if (file_exists($fileNameStyle)) {
                $fileName = $fileNameStyle;
            } else {
                user_error('Style not found: ' . $fileNameStyle . 'trying to use default style');
                $fileName .= '.php';
            }
        }

        if (file_exists($fileName)) {
            extract($data);
            ob_start();
            ?>
            <div class="tpl<?= $className . $style ? ' ' . $style : '' ?>">
                <?php
                include $fileName;
                ?>
            </div>
            <?php
            return ob_get_clean();
        } else {
            throw new Exception('Template not found: ' . $fileName);
        }
    }

}
