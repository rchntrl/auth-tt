<?php
$corePath = dirname(dirname(__FILE__));
define('TEMPLATE_DIR', __DIR__ . '/../view/templates/');

if (!defined('BASE_PATH')) define('BASE_PATH', dirname($corePath));
if (!defined('BASE_URL')) {
    $dir = (strpos($_SERVER['SCRIPT_NAME'], 'index.php') !== false)
        ? dirname($_SERVER['SCRIPT_NAME'])
        : dirname(dirname($_SERVER['SCRIPT_NAME']));
    define('BASE_URL', rtrim($dir, DIRECTORY_SEPARATOR));
}
// IIS will sometimes generate this.
if (!empty($_SERVER['HTTP_X_ORIGINAL_URL'])) {
    $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_ORIGINAL_URL'];
}
// Helper to safely parse and load a querystring fragment
$parseQuery = function ($query) {
    parse_str($query, $_GET);
    if ($_GET) $_REQUEST = array_merge((array)$_REQUEST, (array)$_GET);
};

global $url;
// Apache rewrite rules and IIS use this
if (isset($_GET['url']) && php_sapi_name() !== 'cli-server') {
    // Prevent injection of url= querystring argument by prioritising any leading url argument
    if (isset($_SERVER['QUERY_STRING']) &&
        preg_match('/^(?<url>url=[^&?]*)(?<query>.*[&?]url=.*)$/', $_SERVER['QUERY_STRING'], $results)
    ) {
        $queryString = $results['query'] . '&' . $results['url'];
        $parseQuery($queryString);
    }

    $url = $_GET['url'];

    // IIS includes get variables in url
    $i = strpos($url, '?');
    if ($i !== false) {
        $url = substr($url, 0, $i);
    }

    // Lighttpd and PHP 5.4's built-in webserver use this
} else {
    $url = $_SERVER['REQUEST_URI'];

    // Querystring args need to be explicitly parsed
    if (strpos($url, '?') !== false) {
        list($url, $query) = explode('?', $url, 2);
        $parseQuery($query);
    }

    // Pass back to the webserver for files that exist
    if (php_sapi_name() === 'cli-server' && file_exists(BASE_PATH . $url) && is_file(BASE_PATH . $url)) {
        return false;
    }
}
// Remove base folders from the URL if webroot is hosted in a subfolder
if (substr(strtolower($url), 0, strlen(BASE_URL)) == strtolower(BASE_URL)) $url = substr($url, strlen(BASE_URL));

function __autoload($class_name)
{
    $filename = $class_name . '.php';
    $catalogs = array('core', 'forms', 'forms/constraints', '../controller', '../model', '../forms');
    foreach ($catalogs as $val) {
        $file = __DIR__ . '/' . $val . '/' . $filename;
        if (file_exists($file) == true) {
            include($file);
            break;
        }
    }
    return false;
}

$config = new Config();
require('config.php');
DB::connect($config);
$registry = new Registry($config);
$router = new Router($registry->request);

$routes = array();
require('routes.php');

foreach ($routes as $path => $param) {
    if ($router->isMatch($param['pattern'])) {
        $router->handle($param['pattern'], $param['data'], function() use(&$registry, $path, $param) {
            if ($registry->auth->canAccessPage()) {
                /** @var Controller $controller */
                $controller = new $param['className']($registry);
                $data = $controller->{$path . 'Action'}();
                $controller->customize($data);
                $controller->render('page', $path);
            } else {
                header('Location: ' . BASE_URL . '/login');
            }
        });
    }
}

Controller::handleNotFound();
