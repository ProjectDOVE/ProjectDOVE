<?php
/**
 * This file is part of the ProjectDOVE.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dove\View;


use Mustache_Engine;
use Slim\View;

class MustacheView extends View
{
    /**
     * @var Mustache_Engine
     */
    private $mustache;

    private $templateData;
    /**
     * MustacheView constructor.
     * @param $mustache
     */
    public function __construct(Mustache_Engine $mustache)
    {
        $this->mustache = $mustache;
        parent::__construct();
    }

    protected function render($template,$data = null)
    {
        return $this->mustache->render($template,$this->templateData);
    }
    public function appendData($data){
        $this->templateData = $data;
    }
}