<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


// Views
$routes->get('/', fn() => view('createtasks'));
$routes->get('tasks', fn() => view('tasks'));

// API
$routes->get('api/tasks', 'TaskController::index');
$routes->get('api/tasks/(:num)', 'TaskController::show/$1');
$routes->post('api/tasks', 'TaskController::create');
$routes->patch('api/tasks/(:num)', 'TaskController::update/$1');
$routes->delete('api/tasks/(:num)', 'TaskController::delete/$1');