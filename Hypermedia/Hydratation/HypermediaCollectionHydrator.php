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
    public function hydrate(array $raw)
    {
        $hypermediaClassName = $this->getHypermediaClassName();

        foreach ($raw['data'] as $i => $data) {
            $raw['data'][$i] = $this->hydratationHandler->hydrate($data);
        }

        return new $hypermediaClassName($raw);
    }

    /**
     * {@inheritdoc}
     */
    protected function getHypermediaClassName()
    {
        return '\Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection';
    }
}
