<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


use DateTime;
use Dove\Response\RegisterResponse;
use PDO;
use PDOException;

class RegisterController extends BaseController
{

    public function __invoke(){
        $app = $this->app;


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
        /**
         * @var $db PDO
         *
         */
        $db = $this->app->db;

        if($this->isEmpty($response->username())){
            $response->addError(_("Username is empty"));
        }

        if(strlen($response->username()) < 3){
            $response->addError(_("Username too short"));
        }
        if(strlen($response->username()) > 40){
            $response->addError(_("Username too long"));
        }
        $sql = "SELECT 1 FROM users WHERE username = ".$db->quote($response->username());
        $usernameStatement = $db->query($sql);
        $usernameExists = (bool)$usernameStatement->fetchColumn();
        if($usernameExists){
            $response->addError(_("Username already exists"));
        }


        if($this->isEmpty($response->email())){
            $response->addError(_("Email is empty"));
        }
        $sql ="SELECT 1 FROM users WHERE email = ".$db->quote($response->email());
        $emailStatement = $db->query($sql);
        $emailExists = (bool) $emailStatement->fetchColumn();
        if($emailExists){
            $response->addError(_("Email already exists"));
        }
        if(!filter_var($response->email(),FILTER_VALIDATE_EMAIL)){
            $response->addError(_("Invalid email"));
        }

        if($this->isEmpty($response->password())){
            $response->addError(_("Password is empty"));
        }
        if(strlen($response->password()) < 6){
            $response->addError(_("Password is too short"));
        }
        if(!$response->acceptedTerms()){
            $response->addError(_("Accept the terms"));
        }

        return !$response->hasErrors();
    }

    private function createUser(RegisterResponse $response)
    {
        /**
         * @var $db PDO
         *
         */
        $db = $this->app->db;


        $passwordHash = password_hash($response->password(),PASSWORD_DEFAULT);
        $now = new DateTime();

        $sql ="INSERT INTO users (username,password,email,registrationDate) VALUES(
        ".$db->quote($response->username()).",
        ".$db->quote($passwordHash).",
        ".$db->quote($response->email()).",
        ".$db->quote($now->format('Y-m-d H:i:s'))."
      )";

        try{
            $db->exec($sql);

        }catch (PDOException $e){

            $response->addError($e->getMessage());
        }
    }
}