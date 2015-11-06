<?php

namespace Dove\Response;

/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class LoginResponse extends BaseResponse
{

    /**
     * @access  public
     * @return  string  $username   The login name
     */
    public function username()
    {
        return $this->request->post('username');
    }

    /**
     * @access  public
     * @return  string  $password   The raw password
     */
    public function password()
    {
        return $this->request->post('password');
    }
}