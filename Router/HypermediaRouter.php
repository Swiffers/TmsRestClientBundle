<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Fatory;

use Symfony\Component\Routing\Router;

/**
 * HypermediaRouter
 */
class HypermediaRouter
{
    protected $router;

    /**
     * Constructor
     * 
     * @param $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function generate()
    {
        
    }
}
