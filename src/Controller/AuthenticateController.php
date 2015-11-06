<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


use PDO;

class AuthenticateController extends BaseController
{
    public function before()
    {
        if (!isset($_SESSION['userId'])) {
            $this->app->redirect('/');
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