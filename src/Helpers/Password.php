<?php

namespace Dove\Helpers;

/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Password {
    
    /**
     * @access  public
     * @param   string  $raw    Raw password
     * @return  string  The hashed password
     */
    public function hash($raw) {
        return password_hash($raw ,PASSWORD_DEFAULT);
    }
    
    /**
     * @access  public
     * @param   string  $raw    Raw password
     * @param   string  $hash   Hashed password from database
     * @return  bool    Whether the password was correct (true) or not (false)
     */
    public function check($raw, $hash) {
        return password_verify($raw, $hash);
    }
}