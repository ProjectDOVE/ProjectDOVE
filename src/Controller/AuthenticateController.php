<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


use Dove\Repository\UserRepository;
use PDO;

class AuthenticateController extends BaseController
{
    protected $userdata;

    public function before()
    {
        $app = $this->app;
        if(!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
            $app->redirect("/");
        } else {
            $userRepository = new UserRepository($app->db);
            $this->userdata = $userRepository->findById($_SESSION["id"]);
            if($this->userdata === false) {
                $app->redirect("/");
            }
        }

    }

    public function after()
    {
        if (!isset($_SESSION['userId'])) {
            return;
        }

        /**
         * @var $db PDO
         */
        $db = $this->app->db;
        $sql ="UPDATE users SET lastAction = NOW() WHERE userId = ".(int)$_SESSION['userId'];
        $db->exec($sql);
    }

}