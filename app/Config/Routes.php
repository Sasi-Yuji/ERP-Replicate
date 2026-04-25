<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->get('login', 'Login::index');
$routes->post('login/process', 'Login::process');
$routes->get('logout', 'Login::logout');



$routes->group('', ['filter' => 'auth'], function($routes) {

    // Dashboard
    $routes->get('dashboard', 'Dashboard::index');
    $routes->post('dashboard/create', 'Dashboard::create');
    $routes->get('dashboard/delete/(:num)', 'Dashboard::delete/$1');

    // ── Module 1: Library Management ───────────────────────────────────────
    $routes->group('library', function($routes) {
        $routes->get('/',                  'Library::index');
        $routes->post('create',            'Library::create');           // Admin/Faculty: add book
        $routes->post('update/(:num)',     'Library::update/$1');        // Admin/Faculty: update book
        $routes->get('delete/(:num)',      'Library::delete/$1');        // Admin/Faculty: delete book
        $routes->post('issue',             'Library::issue');            // Student: borrow book
        $routes->get('return/(:num)',      'Library::returnBook/$1');    // Student: return book
        $routes->get('recommend/(:num)',   'Library::recommend/$1');     // Faculty: mark as reference
    });

    // ── Module 2: Examination Management ───────────────────────────────────
    $routes->group('exam', function($routes) {
        $routes->get('/',               'Examination::index');
        $routes->post('upload',         'Examination::uploadMarks');
        $routes->post('upload-paper',   'Examination::uploadPaper');
        $routes->post('schedule-exam',  'Examination::scheduleExam');
        $routes->get('publish',         'Examination::publish');
        $routes->get('schedule',        'Examination::schedule');
        $routes->get('results',         'Examination::results');
    });

    // ── Module 3: Hostel Management ────────────────────────────────────────
    $routes->group('hostel', function($routes) {
        $routes->get('/',                    'Hostel::index');
        $routes->post('apply',               'Hostel::apply');           
        $routes->get('approve/(:num)',       'Hostel::approve/$1');      
        $routes->get('reject/(:num)',        'Hostel::reject/$1');       
        $routes->post('fee-update/(:num)',   'Hostel::updateFee/$1');    
        $routes->get('vacate/(:num)',        'Hostel::vacate/$1');       
        $routes->get('rooms',                'Hostel::rooms');           
        $routes->get('allocation',           'Hostel::allocation');      
    });
});
