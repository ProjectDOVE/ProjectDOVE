<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\WebSocketServer;

use Dove\Helpers\Session;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    private $config;
    private $loop;

    public function __construct($config, $loop)
    {
        $this->clients = new \SplObjectStorage;
        $this->config = $config;

        $this->loop = $loop;
        $this->loop->addPeriodicTimer(1, Array($this, "sendToAll"));

    }

    public function sendToAll() {
        $sentNum = 0;
        $messages = ["Tick", "Tock", "A new GameLoop appeared!", "Something happened!"];

        foreach ($this->clients as $client) {
            $client->send($messages[array_rand($messages, 1)]);
            $sentNum += 1;
        }
        if($sentNum > 0) {
            echo "Sent some stuff to $sentNum clients\n";
        }

    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
