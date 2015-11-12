<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Dove\WebSocketServer;


require_once __DIR__.'/../vendor/autoload.php';

$config = array_merge(
    require_once __DIR__ . '/../config/database.php',
    require_once __DIR__ . '/../config/general.php'
);

$dbConfig = $config['db'];

$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $dbConfig['host'], $dbConfig['dbname']);
$pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$loop = React\EventLoop\Factory::create();

$socket = new React\Socket\Server($loop);
$socket->listen($config["websocket"]["port"], '0.0.0.0');

$wsServer = new WsServer(
    new \Dove\WebSocketServer\WebSocketServer($config, $loop, $pdo)
);
$wsServer->setEncodingChecks(false);



$server = new IoServer(
    new HttpServer(
        $wsServer
    ),
    $socket,
    $loop
);

$server->run();

