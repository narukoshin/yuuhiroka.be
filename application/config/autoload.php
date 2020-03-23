<?php
    spl_autoload_register(function($controller){
        require_once __DIR__ . "/../controllers/{$controller}.php";
    });