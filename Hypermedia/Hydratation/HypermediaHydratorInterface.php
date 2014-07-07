<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Hydratation;

/**
 * HypermediaHydratorInterface is the interface a class should implement
 * to be used as an hydrator of hypermedia.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
interface HypermediaHydratorInterface
{
    /**
     * Hydrate an hypermedia.
     * 
     * @param array $hypermedia The raw hypermedia.
     * 
     * @return object The hypermedia.
     */
    public function hydrate(array $hypermedia);
}
