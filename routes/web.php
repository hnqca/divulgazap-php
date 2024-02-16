<?php

// Middlewares

// Controllers
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GroupController;

/*
|-------------
| Web Routes
|-------------
*/

$route->get('/',             [GroupController::class,    'renderListingPage']);
$route->get('/grupo/enviar', [GroupController::class,    'renderCreationPage']);
$route->get('/grupo/{id}',   [GroupController::class,    'renderDetailsPage']);
$route->get('/categorias',   [CategoryController::class, 'renderListingPage']);