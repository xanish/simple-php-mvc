<?php

// simple autoload function
// converts class name to class path and requires it
spl_autoload_register(function (string $fullyQualifiedClassName) {
    // prepend /
    // change App -> app
    // replace \ with / for directory path
    $classPath = dirname(__DIR__) . '/' . str_replace('\\', '/', lcfirst($fullyQualifiedClassName)) . '.php';

    // if class is not present throw error
    if (!file_exists($classPath)) {
        throw new \App\Exceptions\ClassNotFoundException('Class ' . $fullyQualifiedClassName . ' not found');
    }

    // import class
    require_once $classPath;
});
