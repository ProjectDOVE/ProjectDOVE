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

class BeforeEventSubscriber
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
        /**
         * @var $matchedRoutes Route[]
         */
        $matchedRoutes = $this->app->router->getMatchedRoutes($this->app->request->getMethod(), $this->app->request->getResourceUri());
        foreach($matchedRoutes as $route){
            if(method_exists($route->getCallable(),'before')){
                $route->getCallable()->before();
            }
            return;
        }

    }

}