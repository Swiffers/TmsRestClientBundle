<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Hydratation;

use Tms\Bundle\RestClientBundle\Hypermedia\Constants;

/**
 * HypermediaHydratationHandlerInterface is the interface a class must
 * implement to be used as a hydratation handler for the hypermedia.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
interface HypermediaHydratationHandlerInterface
{
    /**
     * Hydrate an hypermedia.
     * 
     * @param array $hypermedia The raw hypermedia.
     * 
     * @return object The hydrated hypermedia.
     */
    public function hydrate(array $hypermedia);
}
