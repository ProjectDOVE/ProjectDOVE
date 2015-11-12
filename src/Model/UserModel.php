<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Model;


class UserModel extends BaseModel
{
    public $userId;
    public $username;
    public $passwordHash;
    public $lastAction;

    public $registrationDate;
    public $websocketTicket;

    public function convertRegistrationDate(){
         return \DateTime::createFromFormat('Y-m-d H:i:s',$this->registrationDate);
    }
    public function convertUserId(){
        return (int) $this->userId;
    }

}