<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Factory;

use Tms\Bundle\RestBundle\Formatter\AbstractHypermediaFormatter;

/**
 * HypermediaFactory
 */
abstract class HypermediaFactory
{
    /**
     * Build an hypermedia object according to the given raw
     * 
     * @param array $raw
     * 
     * @return HypermediaCollection | HypermediaItem
     */
    static public function build(array $raw)
    {
        if(
            isset($raw['metadata']) &&
            isset($raw['metadata'][AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_NAME])
        ) {
            $hypermediaClass = self::generateHypermediaClass(
                $raw['metadata'][AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_NAME]
            );
            $reflectedClass = new \ReflectionClass($hypermediaClass);

            return new $hypermediaClass($raw);
        }

        throw new \Exception(sprintf(
            "Unable to build hypermedia object : no %s defined in hypermedia metadata.",
            AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_NAME
        ));
    }

    /**
     * Generate the hypermedia class according to serializer context param
     * 
     * @param string $serializerContext
     * @return string hypermedia class name
     */
    static public function generateHypermediaClass($serializerContext)
    {
        $hypermediaType = explode('.', $serializerContext);
        $hypermediaType = ucfirst(array_pop($hypermediaType));
        $hypermediaClass = sprintf("Hypermedia%s", $hypermediaType);

        return sprintf('Tms\Bundle\RestClientBundle\Hypermedia\%s', $hypermediaClass);
    }
}
