<?php

/**
 * var Config $config
 */
$config->siteTitle = 'My site';
$config->mode = 'dev';
$config->lang = 'en';
$config->availableLocales = array(
    array('value' => 'en', 'title' => 'English'),
    array('value' => 'ru', 'title' => 'Russian'),
);

// database config
$config->dbHost = 'localhost';
$config->dbName = 'auth';
$config->dbUser = 'root';
$config->dbPass = '';
$config->dbPort = '3306';

// Security config
$config->accessControl = array(
    '/^\/profile/',
);
