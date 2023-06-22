<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

use App\Utility\TwigLoader;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

session_start();

/**
 * Composer
 */
define("ROOT", realpath(dirname(__FILE__) . "/../") . "/");
define("APP_ROOT", ROOT . "App/");
define("APP_CONFIG_FILE", APP_ROOT . "ConfigurationApp.php");
require_once dirname(__DIR__) . '/vendor/autoload.php';
$loader = new Filesystemloader(dirname(__DIR__) . '/App/Views');
$twig = new Environment($loader, ['debug' => true,]);
$twig->addExtension(new DebugExtension());
TwigLoader::setTwigEnvironment($twig);


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

/*
 * Gestion des erreurs dans le routing
 */
try {
    $router->dispatch($_SERVER['QUERY_STRING']);
} catch (Exception $e) {
    if ($e->getMessage() == 'You must be logged in') {
        header('Location: /login');
    }
}
