<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Response;


use Slim\Http\Request;

abstract class BaseResponse
{
    protected $request;
    public $title;
    public $errors = [];
    public $messages = [];
    public $warnings = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function addError($error)
    {
        $this->errors[] = $error;
    }

    public function addMessage($message)
    {
        $this->messages[] = $message;
    }

    public function addWarning($warning)
    {
        $this->warnings[] = $warning;
    }

    public function hasMessages()
    {
        return count($this->messages) > 0;
    }

    public function hasWarnings()
    {
        return count($this->warnings) > 0;
    }

    public function hasErrors()
    {
        return count($this->errors) > 0;
    }
}