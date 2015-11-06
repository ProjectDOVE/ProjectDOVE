<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Repository;


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
        return "SELECT userId,username,password as passwordHash,lastAction,registrationDate
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
        $sql .= " WHERE id = " .(int)$id;

        $userStatement = $this->connection->query($sql);
        if (!$userStatement) {
            return null;
        }
        $userStatement->setFetchMode(PDO::FETCH_CLASS, '\Dove\Model\UserModel');
        return $userStatement->fetch();

    }
}