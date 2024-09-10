<?php

namespace View;

class Page extends Component
{
    public function render($variables = [], $style = null): string
    {
        $body = parent::render($variables, $style);
        return Component::page(['body' => $body, 'activeLink' => $variables['activeLink'] ?? null]);
    }
}
