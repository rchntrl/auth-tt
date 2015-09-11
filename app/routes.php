<?php

$routes['index'] = array(
    'pattern' => '/\//',
    'data' => null,
    'className' => 'MainController'
);

$routes['changeLocale'] = array(
    'pattern' => '/\/lang\/[\w+]{2}/',
    'data' => array('action', 'lang'),
    'className' => 'MainController'
);

$routes['login'] = array(
    'pattern' => '/\/login/',
    'data' => null,
    'className' => 'MainController'
);

$routes['logout'] = array(
    'pattern' => '/\/logout/',
    'data' => null,
    'className' => 'MainController'
);

$routes['register'] = array(
    'pattern' => '/\/register/',
    'data' => null,
    'className' => 'MainController'
);

$routes['profile'] = array(
    'pattern' => '/\/profile/',
    'data' => null,
    'className' => 'ProfileController'
);

$routes['editProfile'] = array(
    'pattern' => '/\/profile\/edit/',
    'data' => null,
    'className' => 'ProfileController'
);

$routes['editLocation'] = array(
    'pattern' => '/\/profile\/location/',
    'data' => array('page', 'action'),
    'className' => 'ProfileController'
);

$routes['editWork'] = array(
    'pattern' => '/\/profile\/edit-work\/[0-9]+/',
    'data' => array('page', 'action', 'id'),
    'className' => 'ProfileController'
);

$routes['addWork'] = array(
    'pattern' => '/\/profile\/add-work/',
    'data' => null,
    'className' => 'ProfileController'
);

$routes['removeWork'] = array(
    'pattern' => '/\/profile\/remove-work\/[0-9]+/',
    'data' => array('page', 'action', 'id'),
    'className' => 'ProfileController'
);

$routes['addEdu'] = array(
    'pattern' => '/\/profile\/add-edu/',
    'data' => null,
    'className' => 'ProfileController'
);

$routes['editEdu'] = array(
    'pattern' => '/\/profile\/edit-edu\/[0-9]+/',
    'data' => array('page', 'action', 'id'),
    'className' => 'ProfileController'
);

$routes['removeEdu'] = array(
    'pattern' => '/\/profile\/remove-edu\/[0-9]+/',
    'data' => array('page', 'action', 'id'),
    'className' => 'ProfileController'
);

$routes['uploadFile'] = array(
    'pattern' => '/\/upload-file/',
    'data' => array('action', 'file'),
    'className' => 'UploadController'
);
