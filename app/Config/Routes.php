<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('index.php', 'Home::index');
$routes->get('about.php', 'Home::about');
$routes->get('contact.php', 'Home::contact');
