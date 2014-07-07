<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Hydratation;

/**
 * HypermediaItemHydrator is an hydrator for hypermedia of
 * type item.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class HypermediaItemHydrator extends AbstractHypermediaHydrator
{
    /**
     * {@inheritdoc}
     */
    protected function getHypermediaClassName()
    {
        return '\Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem';
    }
}
