<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Fatory;

use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;
use Tms\Bundle\RestClientBundle\Crawler\HypermediaCrawlerHandler;

/**
 * HypermediaFactory
 */
abstract class HypermediaFactory
{
    /**
     * Build an hypermedia object according to given raw
     * 
     * @param array $raw
     * 
     * @return HypermediaCollection | HypermediaItem
     */
    static public function build(array $raw)
    {
        if(isset($raw['metadata']) && isset($raw['metadata']['serializerContextGroup'])) {
            $hypermediaType = ucfirst($raw['metadata']['serializerContextGroup']);
            $hypermediaClass = sprintf("Hypermedia%s", $hypermediaType);
    
            if(class_exists($hypermediaClass)) {
                return new $hypermediaClass($raw);
            }
        }

        throw new \Exception(sprintf(
            "Unable to build hypermedia object : no serializer context group defined."
        ));
    }
}
