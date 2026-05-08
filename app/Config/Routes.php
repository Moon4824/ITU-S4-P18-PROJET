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

// Routes d'inscription groupées.
$routes->group('register', static function ($routes) {
	$routes->get('inscription1', 'RegisterController::inscription1');
	$routes->post('save-inscription1', 'RegisterController::saveInscription1');
	$routes->get('inscription2', 'RegisterController::inscription2');
	$routes->post('save-inscription2', 'RegisterController::saveInscription2');
});

// Routes métier regroupées sans préfixe pour garder les mêmes URLs publiques.
$routes->group('', static function ($routes) {
	$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

	$routes->get('imc', 'ImcController::index', ['filter' => 'auth']);
	$routes->post('imc/calculate', 'ImcController::calculate', ['filter' => 'auth']);

	$routes->get('objectifs', 'ObjectifController::index', ['filter' => 'auth']);
	$routes->post('objectifs/choose', 'ObjectifController::choose', ['filter' => 'auth']);

	// API endpoints for IMC interpretation
	$routes->get('api/imc/interpretations', 'ImcController::list');
	$routes->post('api/imc/calculate', 'ImcController::calculate');
});
