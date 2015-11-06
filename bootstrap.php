<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dove\Controller\GameController;
use Dove\Controller\RegisterController;
use Dove\EventSubscriber\AfterEventSubscriber;
use Dove\EventSubscriber\BeforeEventSubscriber;
use Dove\View\MustacheView;


error_reporting(-1);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/vendor/autoload.php';


$loaders = [];
$loaders[] = new Mustache_Loader_FilesystemLoader(__DIR__ . '/templates');
$mustacheLoader = new Mustache_Loader_CascadingLoader($loaders);



$app = new \Slim\Slim();
$app->config(array_merge(
    require_once __DIR__ . '/config/database.php',
    require_once __DIR__ . '/config/general.php'
));

$mustache = new Mustache_Engine([
    'cache' => $app->config('mustache.cache'),
    'loader' => $mustacheLoader,
    'partials_loader' => $mustacheLoader
]);

$app->view(new MustacheView($mustache));

$app->container->singleton('db', function () use ($app) {
    $dbConfig = $app->config('db');

    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $dbConfig['host'], $dbConfig['dbname']);
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
});


$app->hook('slim.before.dispatch', new BeforeEventSubscriber($app));
$app->hook('slim.after.dispatch', new AfterEventSubscriber($app));
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

$app->get('/game',new GameController($app));
return $app;