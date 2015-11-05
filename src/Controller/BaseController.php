<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


use Slim\Slim;

abstract class BaseController
{
    /**
     * @var Slim
     */
    protected $app;


    public function __construct(Slim $app)
    {
        $this->app = $app;
    }

}