<?php
/**
 * Created by PhpStorm.
 * User: wlady2001
 * Date: 14.08.16
 * Time: 08:46
 */

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigExtension;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use EWallet\Session;
use EWallet\Mobikassa\Sender;
use EWallet\Auth;

include_once __DIR__ . "/../app/bootstrap.php";

$app = new App(["settings" => $config]);

// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new Twig(APPDIR . '/tpl', [
        'cache' => TWIG_CACHE_DIR
    ]);
    $view->addExtension(new TwigExtension(
        $container['router'],
        $container['request']->getUri()
    ));
    $view->getEnvironment()->addFilter(new Twig_SimpleFilter('ebase64', 'base64_encode'));
    return $view;
};
$container['db'] = function ($container) {
    $db = $container['settings']['db'];
    $pdo = new PDO("sqlite:" . $db['path']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    return $pdo;
};
$container['logger'] = function () {
    $logger = new Logger('app');
    $file_handler = new StreamHandler(TMPDIR . "/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};
$container['session'] = function ($container) {
    return new Session('app', $container);
};
$container['auth'] = function ($container) {
    return new Auth($container);
};
$container['mobikassa'] = function ($container) {
    return new Sender($container);
};
include_once APPDIR . '/routers/Router.php';

$app->run();
