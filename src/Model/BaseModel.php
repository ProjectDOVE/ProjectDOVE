<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Model;


abstract class BaseModel
{
    public function __set($name,$value){
        $methodName = 'modify'.ucfirst($name);

        if(method_exists($this,$methodName)){
            $this->$methodName($value);
        }
    }
    public function __get($name){
        $methodName = 'convert'.ucfirst($name);
        if(method_exists($this,$methodName)){
            return $this->$methodName();
        }
    }
}