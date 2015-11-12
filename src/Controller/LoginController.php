<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


use Dove\Response\LoginResponse;
use Dove\Repository\UserRepository;
use Dove\Model\UserModel;

class LoginController extends BaseController
{
    public function __invoke(){
        $app = $this->app;
        /**
         * @var $db \PDO
         */
        $db = $app->db;

        $response = new LoginResponse($app->request);
        if($this->validateLogin($response)) {
            $app->redirect("/game");
        } else {
            $app->render('pages/landing', $response);
        }
    }

    private function validateLogin(LoginResponse $response) {
        $userRepository = new UserRepository($this->app->db);

        $user = $userRepository->findByUsername($response->username());
        if($user === false) {
            $response->addError(_("Invalid Password or Username."));
            return false;
        }
        if(password_verify($response->password(), $user->passwordHash) === true) {

            //generate a new ticket on each login
            $userRepository->regenerateWebsocketTicket($user->userId);
            $_SESSION["id"] = $user->userId;
            return true;
        } else {
            $response->addError(_("Invalid Password or Username."));
            return false;
        }

    }
}