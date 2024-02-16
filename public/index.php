<?php

    require_once __DIR__ . '/../vendor/autoload.php';

    use App\Helpers\Env;
    use App\Http\Router;

    session_start();
    date_default_timezone_set(Env::get("APP_TIMEZONE"));

    $route = new Router(Env::get("APP_URL"));

    require_once __DIR__ . '/../routes/web.php';
    require_once __DIR__ . '/../routes/api.php';