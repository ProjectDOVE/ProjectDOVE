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
use Dove\Repository\UserRepository;

class RegisterController extends BaseController
{

    public function __invoke()
    {
        $app = $this->app;

        $response = new RegisterResponse($app->request);
        $response->title = _("Register");

        //only validate if actual post data was sent
        if ($app->request->isPost() && $this->isValid($response)) {
            $this->createUser($response);
        }

        $app->render('pages/register', $response);
    }

    private function isEmpty($value)
    {
        return in_array($value, ['', null, false]);
    }

    private function isValid(RegisterResponse $response)
    {
        /**
         * @var $db PDO
         *
         */
        $db = $this->app->db;

        if ($this->isEmpty($response->username())) {
            $response->addError(_("Username is empty"));
        }

        if (strlen($response->username()) < 3) {
            $response->addError(_("Username too short (min. 3 characters)"));
        }
        if (strlen($response->username()) >= 40) {
            $response->addError(_("Username too long (max. 40 characters)"));
        }
        $sql = "SELECT 1 FROM users WHERE username = " . $db->quote($response->username());
        $usernameStatement = $db->query($sql);
        $usernameExists = (bool)$usernameStatement->fetchColumn();
        if ($usernameExists) {
            $response->addError(_("Username already exists"));
        }


        if ($this->isEmpty($response->email())) {
            $response->addError(_("Email is empty"));
        }
        $sql = "SELECT 1 FROM users WHERE email = " . $db->quote($response->email());
        $emailStatement = $db->query($sql);
        $emailExists = (bool)$emailStatement->fetchColumn();
        if ($emailExists) {
            $response->addError(_("Email already exists"));
        }
        if (!filter_var($response->email(), FILTER_VALIDATE_EMAIL)) {
            $response->addError(_("Invalid email"));
        }

        if ($this->isEmpty($response->password())) {
            $response->addError(_("Password is empty"));
        }
        if (strlen($response->password()) < 6) {
            $response->addError(_("Password is too short (min. 6 characters)"));
        }
        if (!$response->acceptedTerms()) {
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
        $userRepository = new UserRepository( $this->app->db );


        $passwordHash = password_hash($response->password(), PASSWORD_DEFAULT);

        try {
            $userRepository->add($response->username(), $passwordHash, $response->email());
            $response->addMessage(_('You have successfully registered. Proceed to <a href="/">Log In</a>'));
        } catch (PDOException $e) {

            $response->addError($e->getMessage());
        }
    }
}