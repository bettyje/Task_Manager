<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('tasks', 'TaskController::index');
$routes->post('tasks', 'TaskController::create');
$routes->patch('tasks/(:num)', 'TaskController::update/$1');
$routes->delete('tasks/(:num)', 'TaskController::delete/$1');