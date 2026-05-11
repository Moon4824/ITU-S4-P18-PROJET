<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

// Objectifs successifs avec URL publique, protégée par le filtre utilisateur.
$routes->match(['get', 'post'], 'objectifs/choose', 'ObjectifController::choose', ['filter' => 'user']);
$routes->post('objectifs/choose/save', 'ObjectifController::save', ['filter' => 'user']);
$routes->post('objectifs/choose/pdf', 'ObjectifController::exportPdf', ['filter' => 'user']);

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
$routes->group('user', ['filter' => 'user'] , static function ($routes) {
	$routes->get('/', 'UserController::index');

	$routes->get('profile', 'UserController::profile');
	$routes->get('profile/edit', 'UserController::editProfile');
	$routes->post('profile/update', 'UserController::updateProfile');

	$routes->get('imc', 'ImcController::index');
	$routes->post('imc/calculate', 'ImcController::calculate');

	$routes->get('objectifs', 'ObjectifController::index');
	$routes->post('objectifs/choose', 'ObjectifController::choose');

});
	// API endpoints for IMC interpretation
	$routes->get('api/imc/interpretations', 'ImcController::list');
	$routes->post('api/imc/calculate', 'ImcController::calculate');

$routes->group('admin', ['filter' => 'admin'], function($routes) {
	// Controllers are in App\Controllers\ (RegimeController), not in Admin namespace.
	$routes->get('/', 'AdminController::index');
	$routes->get('regime', 'RegimeController::index');
	$routes->get('regime/create', 'RegimeController::create');
	$routes->post('regime/store', 'RegimeController::store');
	$routes->get('regime/edit/(:num)', 'RegimeController::edit/$1');
	$routes->post('regime/update/(:num)', 'RegimeController::update/$1');
	// $routes->get('regime/delete/(:num)', 'RegimeController::delete/$1');
	// Allow deletion via POST form submission (CSRF-protected)
	$routes->post('regime/delete/(:num)', 'RegimeController::delete/$1');

	$routes->get('sports',                'Admin\SportController::index');
    $routes->get('sports/create',         'Admin\SportController::create');
    $routes->post('sports/store',         'Admin\SportController::store');
    $routes->get('sports/show/(:num)',    'Admin\SportController::show/$1');
    $routes->get('sports/edit/(:num)',    'Admin\SportController::edit/$1');
    $routes->post('sports/update/(:num)', 'Admin\SportController::update/$1');
    $routes->get('sports/delete/(:num)',  'Admin\SportController::delete/$1');

	$routes->get('utilisateurs',                'Admin\UtilisateurController::index');
	$routes->get('utilisateurs/create',         'Admin\UtilisateurController::create');
	$routes->post('utilisateurs/store',         'Admin\UtilisateurController::store');
	$routes->get('utilisateurs/show/(:num)',    'Admin\UtilisateurController::show/$1');
	$routes->get('utilisateurs/edit/(:num)',    'Admin\UtilisateurController::edit/$1');
	$routes->post('utilisateurs/update/(:num)', 'Admin\UtilisateurController::update/$1');
	$routes->get('utilisateurs/delete/(:num)',  'Admin\UtilisateurController::delete/$1');

	$routes->get('codes',                'Admin\CodeArgentController::index');
	$routes->get('codes/toggle/(:num)',                'Admin\CodeArgentController::toggle/$1');
	$routes->get('codes/create',         'Admin\CodeArgentController::create');
	$routes->post('codes/store',         'Admin\CodeArgentController::store');
	$routes->get('codes/show/(:num)',    'Admin\CodeArgentController::show/$1');
	$routes->get('codes/edit/(:num)',    'Admin\CodeArgentController::edit/$1');
	$routes->post('codes/update/(:num)', 'Admin\CodeArgentController::update/$1');
	$routes->get('codes/delete/(:num)',  'Admin\CodeArgentController::delete/$1');

	// Routes Gold configuration
	$routes->get('gold',           'Admin\GoldController::index');
	$routes->post('gold/update',   'Admin\GoldController::update');
	$routes->get('api/gold/config', 'Admin\GoldController::getConfig');
});
