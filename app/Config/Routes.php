<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');

// Routes d'authentification groupées.
$routes->group('auth', static function ($routes) {
	$routes->get('login', 'AuthController::login');
	$routes->post('login', 'AuthController::login');
	$routes->post('logout', 'AuthController::logout', ['filter' => 'auth']);
});

$routes->get('dashboard', 'AuthController::dashboard', ['filter' => 'auth']);
