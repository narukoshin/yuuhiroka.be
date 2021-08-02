<?php
    spl_autoload_register(function($controller){
        $file = __DIR__ . "/../controllers/{$controller}.php";
        if (file_exists($file)) require_once $file;
    });
    spl_autoload_register(function($class){
        $file = __DIR__ . "/../classes/{$class}.php";
        if (file_exists($file)) require_once $file;
    });