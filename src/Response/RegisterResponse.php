<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Response;

class RegisterResponse extends BaseResponse
{

    public function username(){
        return $this->request->post('username');
    }
    public function password(){
        return $this->request->post('password');
    }
    public function email(){
        return $this->request->post('email');
    }
    public function acceptedTerms(){
        return $this->request->post('acceptedTerms') === 'on';
    }

    public $registrationSuccessful = false;


}