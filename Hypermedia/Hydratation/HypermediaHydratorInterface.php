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
     * Set an hydratation handler.
     *
     * @param HypermediaHydratationHandlerInterface $hydratationHandler.
     */
    public function setHydratationHandler(HypermediaHydratationHandlerInterface $hydratationHandler);

    /**
     * Hydrate an hypermedia.
     *
     * @param array $hypermedia The raw hypermedia.
     *
     * @return object The hypermedia.
     */
    public function hydrate(array $hypermedia);
}
