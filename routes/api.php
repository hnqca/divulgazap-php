<?php

// Middlewares

// Controllers
use App\Http\Controllers\GroupController;

/*
|-------------
| API Routes
|-------------
*/

$route->post('/api/group',                     [GroupController::class, 'create']);
$route->get('/api/group/validate-link/{link}', [GroupController::class, 'validateLink']);