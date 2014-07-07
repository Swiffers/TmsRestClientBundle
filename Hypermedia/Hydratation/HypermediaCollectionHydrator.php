<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Hydratation;

/**
 * HypermediaCollectionHydrator is an hydrator for hypermedia of
 * type collection.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class HypermediaCollectionHydrator extends AbstractHypermediaHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getHypermediaClassName()
    {
        return '\Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection';
    }
}
