<?php

namespace App;

use App\Exceptions\FileNotFoundException;

class Config
{
    protected array $constants = [];

    public function register(string $key)
    {
        $file = dirname(__DIR__) . '/config/' . $key . '.php';

        if (!file_exists($file)) {
            throw new FileNotFoundException('Config file ' . $file . ' not found');
        }

        $this->constants[$key] = require_once $file;
    }

    // using getter here to allow clean access to properties
    // for example: $config->db instead of $config->get('db')
    public function __get(string $key): mixed
    {
        return $this->constants[$key] ?? null;
    }
}
