<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Repository;

use DateTime;
use PDO;

class UserRepository
{
    /**
     * @var PDO
     */
    private $connection;

    /**
     * UserRepository constructor.
     * @param $connection
     */
    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    private function getSql()
    {
        return "SELECT userId,username,password as passwordHash,lastAction,registrationDate, websocketTicket
                FROM users";

    }

    public function findByUsername($username)
    {
        $sql = $this->getSql();
        $sql .= " WHERE username = " . $this->connection->quote($username);

        $userStatement = $this->connection->query($sql);
        if (!$userStatement) {
            return null;
        }
        $userStatement->setFetchMode(PDO::FETCH_CLASS, '\Dove\Model\UserModel');
        return $userStatement->fetch();

    }
    public function findByEmail($email)
    {
        $sql = $this->getSql();
        $sql .= " WHERE email = " . $this->connection->quote($email);

        $userStatement = $this->connection->query($sql);
        if (!$userStatement) {
            return null;
        }
        $userStatement->setFetchMode(PDO::FETCH_CLASS, '\Dove\Model\UserModel');
        return $userStatement->fetch();

    }
    public function findById($id)
    {
        $sql = $this->getSql();
        $sql .= " WHERE userId = " .(int)$id;

        $userStatement = $this->connection->query($sql);
        if (!$userStatement) {
            return null;
        }
        $userStatement->setFetchMode(PDO::FETCH_CLASS, '\Dove\Model\UserModel');
        return $userStatement->fetch();

    }
    public function add($username, $passwordHash, $email) {
        $now = new DateTime();

        $sql = "INSERT INTO users (username,password,email,registrationDate) VALUES(
        " . $this->connection->quote($username) . ",
        " . $this->connection->quote($passwordHash) . ",
        " . $this->connection->quote($email) . ",
        " . $this->connection->quote($now->format('Y-m-d H:i:s')) . "
        )";
        $this->connection->exec($sql);
    }

    public function regenerateWebsocketTicket($id) {

        $bytes = openssl_random_pseudo_bytes(15);
        $newTicket = bin2hex($bytes);

        $sql = "UPDATE users SET websocketTicket = " . $this->connection->quote($newTicket) . " WHERE userId = ". $this->connection->quote($id);

        $this->connection->exec($sql);

    }
}