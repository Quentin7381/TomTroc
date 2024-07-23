<?php

namespace View;

class Page extends Component {
    public function render($variables = [], $style = null): string {
        $body = parent::render($variables, $style);
        $html = Component::page(['body' => $body]);
        $view = View::getInstance();
        $view->addCss($html);
        $view->addJs($html);
        return $html;
    }
}
