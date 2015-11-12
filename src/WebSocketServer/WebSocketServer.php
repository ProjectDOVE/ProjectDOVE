<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\WebSocketServer;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

use Dove\Repository\UserRepository;
use Dove\Model\UserModel;
use PDO;

class WebSocketServer implements MessageComponentInterface
{
    protected $clients;
    private $config;
    private $loop;
    private $db;

    public function __construct($config, $loop, $db)
    {
        $this->clients = new \SplObjectStorage;
        $this->config = $config;
        $this->loop = $loop;
        $this->db = $db;
//         $this->loop->addPeriodicTimer(1, Array($this, "sendToAll"));

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
        $json = json_decode($msg, true);
        if(is_null($json)) {
            echo $msg."\n";
            echo "Recieved invalid JSON from connection {$from->resourceId}\n";
            return;
        }
        if(is_null($this->clients[$from])) { //user has not authenticated. only accept authentication messages
            if(array_key_exists("user", $json) && array_key_exists("ticket", $json)) {
                //this should be done asynchronously...
                $userRepository = new UserRepository($this->db);

                $user = $userRepository->findByUsername($json["user"]);
                if($user === false || $user->websocketTicket !== $json["ticket"]) {
                    echo "Recieved invalid USER AUTH from connection {$from->resourceId}\n";
                    $this->clients->detach($from);
                    $from->close();
                } else {
                    echo "Authenticated user {$user->username} on connection {$from->resourceId}\n";
                    $this->clients[$from] = $user;
                }

            }

        } else {
            if(array_key_exists("msg", $json)) {
                foreach ($this->clients as $client) {
                    if ($from !== $client) {
                        // The sender is not the receiver, send to each client connected
                        $client->send(json_encode(Array("user" => $this->clients[$from]->username, "message" => $json["msg"])));
                    }
                }
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
