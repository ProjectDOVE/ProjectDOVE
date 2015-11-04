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


$loaders = [];
$loaders[]=new Mustache_Loader_FilesystemLoader(__DIR__.'/templates');
$mustacheLoader = new Mustache_Loader_CascadingLoader($loaders);
$mustache = new Mustache_Engine([
    'loader'=>$mustacheLoader,
    'partials_loader'=>$mustacheLoader
]);

$app = new \Slim\Slim([
    'view'=>new \Dove\View\MustacheView($mustache)
]);

$app->get('/',function()use($app){

    $app->render('pages/landing',['body'=>'test']);
});
return $app;