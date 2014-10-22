<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Hydratation;

/**
 * HypermediaArrayHydrator is an hydrator for hypermedia of
 * type array.
 *
 * @author Gabriel Bondaz <gabriel.bondaz@idci-consulting.fr>
 */
class HypermediaArrayHydrator extends AbstractHypermediaHydrator
{
    /**
     * {@inheritdoc}
     */
    public function hydrate(array $raw)
    {
        $hypermediaClassName = $this->getHypermediaClassName();

        return new $hypermediaClassName($raw);
    }

    /**
     * {@inheritdoc}
     */
    protected function getHypermediaClassName()
    {
        return '\Tms\Bundle\RestClientBundle\Hypermedia\HypermediaArray';
    }
}
