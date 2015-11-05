<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


use Dove\Response\RegisterResponse;

class RegisterController extends BaseController
{

    public function __invoke(){
        $app = $this->app;
        $response = new RegisterResponse($app->request);
        $response->title = _("Register");
        $app->render('pages/register',$response);
    }
}