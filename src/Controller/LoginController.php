<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


use Dove\Response\LoginResponse;

class LoginController extends BaseController
{
    public function __invoke(){
        $app = $this->app;
        /**
         * @var $db \PDO
         */
        $db = $app->db;

        $response = new LoginResponse($app->request);
        $app->render('pages/landing', $response);
    }
}