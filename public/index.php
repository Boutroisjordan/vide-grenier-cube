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
use Dotenv\Dotenv;

session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * Composer
 */
$loader = new Filesystemloader(dirname(__DIR__) . '/App/Views');
$twig = new Environment($loader, ['debug' => true,]);
$twig->addExtension(new DebugExtension());

TwigLoader::setTwigEnvironment($twig);

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

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
} catch(Exception $e){
    if ($e->getMessage() == 'You must be logged in') {
        header('Location: /login');
    }
}