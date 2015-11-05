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


$app = require_once __DIR__.'/../vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new \Dove\WebSocketServer\WebSocketServer()
        )
    ),
    8080
);

$server->run();

