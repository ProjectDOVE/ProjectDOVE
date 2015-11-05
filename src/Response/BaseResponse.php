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
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

}