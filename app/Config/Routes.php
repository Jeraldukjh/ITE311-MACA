<?php

use CodeIgniter\Router\RouteCollection;
use App\Filters\RoleFilter;

/**
 * @var RouteCollection $routes
 */

// Home routes
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// API routes
$routes->group('api', ['namespace' => 'App\Controllers', 'filter' => 'csrf'], function($routes) {
    $routes->get('csrf', 'Api::csrf');
});

// Authentication routes
$routes->group('', ['namespace' => 'App\Controllers'], function($routes) {
    // Public routes
    $routes->get('/register', 'Auth::register');
    $routes->post('/register', 'Auth::register');
    $routes->get('/login', 'Auth::login');
    
    // Login POST route
    $routes->post('/login', 'Auth::login');

    $routes->group('', ['filter' => 'auth'], function($routes) {
        $routes->get('/logout', 'Auth::logout');
        $routes->get('/dashboard', 'Auth::dashboard', ['as' => 'dashboard']);
        
        // Admin routes
        $routes->group('admin', ['filter' => 'role:admin'], function($routes) {
            // Courses management
            $routes->get('courses', 'Admin\Courses::index');
            $routes->get('courses/create', 'Admin\Courses::create');
            $routes->post('courses', 'Admin\Courses::store', ['filter' => 'csrf']);
        });
        
        // Student routes
        $routes->group('student', ['filter' => 'role:student'], function($routes) {
            $routes->get('courses', 'Student\Enroll::index', ['as' => 'student.courses']);
            $routes->get('courses/list', 'Student\Enroll::courses', ['as' => 'student.courses.list']);
            
            // Enrollment route - using the Student\Enroll controller
            $routes->post('enroll', 'Student\Enroll::enroll', ['as' => 'student.enroll', 'filter' => 'csrf']);
        });
    });
});
