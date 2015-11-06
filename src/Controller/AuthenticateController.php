<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


class AuthenticateController extends BaseController
{
    public function before()
    {
        var_dump("before");
    }

    public function after()
    {
        var_dump("after");
    }

}