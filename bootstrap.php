<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dove\Controller\RegisterController;


error_reporting(-1);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';


$loaders = [];
$loaders[] = new Mustache_Loader_FilesystemLoader(__DIR__ . '/templates');
$mustacheLoader = new Mustache_Loader_CascadingLoader($loaders);
$mustache = new Mustache_Engine([
    'loader' => $mustacheLoader,
    'partials_loader' => $mustacheLoader
]);


$app = new \Slim\Slim([
    'view' => new \Dove\View\MustacheView($mustache)
]);
$app->config(array_merge(
    require_once __DIR__ . '/config/database.php',
    require_once __DIR__ . '/config/general.php'
));

$app->container->singleton('db', function () use ($app) {
    $dbConfig = $app->config('db');

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $dbConfig['host'], $dbConfig['dbname']);
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
});


$app->get('/', function () use ($app) {

    $app->render('pages/landing', ['body' => 'test']);
});

$app->post('/login', function () use ($app) {
    /**
     * @var $db PDO
     */
    $db = $app->db;

    $response = [];
    $app->render('pages/landing', $response);
});

$app->map('/register', new RegisterController($app))->via('GET', 'POST');

$app->get('/game', function () use ($app) {
    $wsConfig = $app->config('websocket');
    $app->render('pages/ingame', ['body' => 'Muh', "websocket" => $wsConfig["server"] . ":" . $wsConfig["port"]]);
});
return $app;