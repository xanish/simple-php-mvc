<?php

namespace App;

use App\Exceptions\ViewNotFoundException;

class View
{
    public function render(
        string $view,
        array $params = []
    ) {
        // super simple render function, does not support 
        // view / layout extension

        $viewFile = dirname(__DIR__) . '/views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            throw new ViewNotFoundException('View ' . $viewFile . ' not found');
        }

        ob_start();

        // $params should be available in view to access params
        // params can be directly accessed as $params['someData']
        require_once $viewFile;

        return ob_get_clean();
    }

    public function __get(string $param)
    {
        return $this->params[$param] ?? null;
    }
}
