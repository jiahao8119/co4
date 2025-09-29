<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/api/hello', 'Api::hello');
$routes->post('login', 'Auth::login');
$routes->get('profile', 'Auth::profile');


