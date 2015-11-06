<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\Controller;


class LandingPageController extends BaseController
{
 public function __invoke(){
     $app = $this->app;
     $app->render('pages/landing', ['body' => 'test']);
 }
}