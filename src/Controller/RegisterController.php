<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


use Dove\Response\RegisterResponse;
use PDO;

class RegisterController extends BaseController
{

    public function __invoke(){
        $app = $this->app;
        /**
         * @var PDO
         *
         */
        $db = $app->db;

        $response = new RegisterResponse($app->request);
        $response->title = _("Register");
        if($this->isValid($response)){
            $this->createUser($response);
        }

        $app->render('pages/register',$response);
    }
    private function isEmpty($value){
        return in_array($value,['',null,false]);
    }
    private function isValid(RegisterResponse $response){
        if($this->isEmpty($response->username())){
            $response->addError(_("Username is empty"));
        }
        if(strlen($response->username()) < 3){
            $response->addError(_("Username too short"));
        }
        if(strlen($response->username()) > 40){
            $response->addError(_("Username too long"));
        }
        if($this->isEmpty($response->email())){
            $response->addError(_("Email is empty"));
        }
        if(!filter_var($response->email(),FILTER_VALIDATE_EMAIL)){
            $response->addError(_("Invalid email"));
        }
        if(!$response->acceptedTerms()){
            $response->addError(_("Accept the terms"));
        }
    }

    private function createUser(RegisterResponse $response)
    {
    }
}