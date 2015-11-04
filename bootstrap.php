<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

error_reporting(-1);
ini_set('display_errors',1);

require_once __DIR__.'/vendor/autoload.php';

$app = new \Slim\Slim();

$app->get('/',function()use($app){
   echo "Hello World";
});
return $app;