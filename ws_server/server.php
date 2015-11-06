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

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new \Dove\WebSocketServer\WebSocketServer($config)
        )
    ),
    $config["websocket"]["port"]
);

$server->run();

