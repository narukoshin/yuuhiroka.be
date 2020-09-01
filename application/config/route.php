<?php
    // docs:https://nezamy.com/route
    define('DS', DIRECTORY_SEPARATOR);
    define('BASE_PATH', dirname(__DIR__) . DS);

    $app            = System\App::instance();
    $app->request   = System\Request::instance();
    $app->route     = System\Route::instance($app->request);

    $route = $app->route;

    // Home page
    $route->group('/', function(){
        // Home
        $this->get('/', 'home@index');

        // Languages
        $this->get('/lv', 'home@lang_lv'); // Latvian language
        $this->get('/en', 'home@lang_en'); // English language
        $this->get('/ru', 'home@lang_ru'); // Russian language
        $this->get('/ja', 'home@lang_ja'); // Japanese language

        // Sending #contact message
        $this->get('/send-message', 'home@send_message');
        $this->post('/send-message', 'home@send_message');
    });

    // Admin page
    $route->group('/admin', function(){
        // Admin login
        $this->get('/', 'admin@index');
        // Admin login auth
        $this->any('/login', 'admin@login');
        // Admin dashboard
        $this->get('/dashboard', 'admin@dashboard');
        $this->get(['/logout', '/logout/{token}'], 'admin@logout');
        // If user don't have created password, redirect to page to create password
        $this->any('/create-password', 'admin@create_password');
    });

    // 404 page
    $route->any('/*', function(){
        ob_start();
        include_once 'application/views/404.html';
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    });

    $route->end();