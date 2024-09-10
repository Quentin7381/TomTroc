<?php

namespace View;

class Page extends Component
{
    public function render($variables = [], $style = null): string
    {
        $body = parent::render($variables, $style);
        $html = Component::page(['body' => $body, 'activeLink' => $variables['activeLink'] ?? null]);
        $view = View::getInstance();
        $view->embedCss($html);
        $view->embedJs($html);
        return $html;
    }
}
