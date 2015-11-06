<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\EventSubscriber;


use Slim\Route;
use Slim\Slim;

class AfterEventSubscriber
{
    private $app;

    /**
     * BeforeAfterEventSubscriber constructor.
     * @param $app
     */
    public function __construct(Slim $app)
    {
        $this->app = $app;

    }

    public function __invoke()
    {
        $route = $this->app->router->getCurrentRoute();
        if (method_exists($route->getCallable(), 'after')) {
            $route->getCallable()->after();
        }
    }

}