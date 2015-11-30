<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;
use Dove\Repository\UserRepository;
use Dove\Model\UserModel;


class GameController extends AuthenticateController
{
    public function __invoke()
    {

        $app = $this->app;
        $wsConfig = $app->config('websocket');

        $app->render('pages/ingame', [
            "websocket" => $wsConfig["server"] . ":" . $wsConfig["port"],
            "user" => $this->userdata->username,
            "wsTicket" => $this->userdata->websocketTicket
        ]);
    }

}