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

        // Courses search (available to authenticated users)
        $routes->get('/courses/search', 'Course::search');
        $routes->post('/courses/search', 'Course::search');

        // Notifications API
        $routes->get('/notifications', 'Notifications::get');
        $routes->post('/notifications/mark_read/(:num)', 'Notifications::mark_as_read/$1');

        // Materials routes (accessible by authenticated users)
        $routes->get('materials/download/(:num)', 'Materials::download/$1');
        
        // Admin routes
        $routes->group('admin', ['filter' => 'role:admin,teacher'], function($routes) {
            // Users management (admin only will be enforced in controller)
            $routes->get('users', 'Admin\Users::index');
            $routes->get('users/create', 'Admin\Users::create');
            $routes->post('users', 'Admin\Users::store', ['filter' => 'csrf']);
            $routes->get('users/(:num)/edit', 'Admin\Users::edit/$1');
            $routes->post('users/(:num)', 'Admin\Users::update/$1', ['filter' => 'csrf']);
            $routes->post('users/(:num)/delete', 'Admin\Users::delete/$1', ['filter' => 'csrf']);

            // Courses management
            $routes->get('courses', 'Admin\Courses::index');
            $routes->get('courses/create', 'Admin\Courses::create');
            $routes->post('courses', 'Admin\Courses::store', ['filter' => 'csrf']);

            // Materials management
            $routes->get('course/(:num)/upload', 'Materials::upload/$1');
            $routes->post('course/(:num)/upload', 'Materials::upload/$1');
            $routes->get('materials/delete/(:num)', 'Materials::delete/$1');
        });
        
        // Student routes
        $routes->group('student', ['filter' => 'role:student'], function($routes) {
            $routes->get('courses', 'Student\Enroll::index', ['as' => 'student.courses']);
            $routes->get('courses/list', 'Student\Enroll::courses', ['as' => 'student.courses.list']);
            
            // Enrollment route - using the Student\Enroll controller
            $routes->post('enroll', 'Student\Enroll::enroll', ['as' => 'student.enroll', 'filter' => 'csrf']);

            // Student materials view
            $routes->get('course/(:num)/materials', 'Materials::courseMaterials/$1');
        });

        // Teacher routes
        $routes->group('teacher', ['filter' => 'role:teacher'], function($routes) {
            $routes->get('courses', 'Teacher\\Courses::index', ['as' => 'teacher.courses']);
            $routes->get('course/(:num)/students', 'Teacher\\Courses::students/$1', ['as' => 'teacher.course.students']);
        });
    });
});

