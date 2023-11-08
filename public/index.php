<?php
    header('Access-Control-Allow-Headers: *');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');

    date_default_timezone_set('America/Sao_Paulo');
    setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');

    require_once __DIR__ . '/../vendor/autoload.php';

    use App\Helpers\Env;
    use CoffeeCode\Router\Router;
    use App\Controllers\ErrorController;

    try {

        $router = new Router(Env::get('APP_URL'));

        $router->namespace("App\Controllers")->group(null);
        $router->get("/",           "GroupController:renderPageListing");
        $router->get("/categories", "CategoryController:renderPage");

        $router->group("group");
        $router->get("/{id}",   "GroupController:renderPageDetail");
        $router->get("/create", "GroupController:renderPageCreate");

        $router->group("api");
        $router->post("/validate-link", "GroupController:findGroupData");
        $router->post("/group",         "GroupController:create");
        $router->get("/groups",         "GroupController:read");

        if (!$router->dispatch()) {
            throw new \Exception($router->error());
        }
    
    } catch (\Exception $e) {
        return ErrorController::getErrorMessage($e);
    }