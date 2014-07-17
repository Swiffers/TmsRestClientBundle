<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Hydratation;

/**
 * AbstractHypermediaHydrator is an helper class to define
 * hypermedia hydrators.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
abstract class AbstractHypermediaHydrator implements HypermediaHydratorInterface
{
    /**
     * The hydratation handler.
     *
     * @var HypermediaHydratationHandlerInterface
     */
    protected $hydratationHandler;

    /**
     * {@inheritdoc}
     */
    public function setHydratationHandler(HypermediaHydratationHandlerInterface $hydratationHandler)
    {
        $this->hydratationHandler = $hydratationHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function hydrate(array $raw)
    {
        $hypermediaClassName = $this->getHypermediaClassName();

        return new $hypermediaClassName($raw);
    }

    /**
     * Get the name of the class of the hypermedia.
     *
     * @return string The name of the class.
     */
    abstract protected function getHypermediaClassName();
}
