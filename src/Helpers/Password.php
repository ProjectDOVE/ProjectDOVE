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
     * @access  private
     * @var     string      The used hashing algorithm
     */
    private function $algo = PASSWORD_DEFAULT;
    
    /**
     * @access  public
     * @param   string|null $algo   Optional parameter to change the default hashing algo
     */
    public function __construct($algo = null) {
        if (is_string($algo)) {
            $this->algo = $algo;
        }
    }
    
    /**
     * @access  public
     * @param   string  $raw    Raw password
     * @return  string  The hashed password
     */
    public function hash($raw) {
        return password_hash($raw, $this->getAlgo());
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
    
    /**
     * @access  public
     * @param   string  $hash   The hashed password
     * @return  bool    Whether the password needs to be rehashed
     */
    public function needsRehash($hash) {
        return password_needs_rehash($hash, $this->getAlgo());
    }
    
    /**
     * @access  public
     * @return  string  The hashing algorithm used in this class
     */
    public function getAlgo() {
        return $this->algo;
    }
}