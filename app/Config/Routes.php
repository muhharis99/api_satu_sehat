<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->setDefaultNamespace('App\\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Home::index');
$routes->get('health', 'Home::health');

$routes->post('satusehat/token', 'SatuSehat::token');
$routes->post('satusehat/request', 'SatuSehat::request');
$routes->get('satusehat/history', 'SatuSehat::history');

$routes->get('playbook', 'Playbook::index');
$routes->get('playbook/(:segment)', 'Playbook::show/$1');
$routes->get('playbook/template/(:segment)', 'Playbook::template/$1');

$routes->get('terminology/systems', 'Terminology::systems');
$routes->post('terminology/search', 'Terminology::search');
