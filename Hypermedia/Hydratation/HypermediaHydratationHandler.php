<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Hydratation;

use Tms\Bundle\RestClientBundle\Hypermedia\Constants;

/**
 * HypermediaHydratationHandler is a basic implementation of
 * an hydratation handler for the hypermedia.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class HypermediaHydratationHandler implements HypermediaHydratationHandlerInterface
{
    /**
     * The hydrators.
     *
     * @var array
     */
    protected $hydrators = array();

    /**
     * Set a hydrator.
     *
     * @param string            $id       The id of the hydrator.
     * @param HydratorInterface $hydrator The hydrator.
     */
    public function setHydrator($id, HypermediaHydratorInterface $hydrator)
    {
        $this->hydrators[$id] = $hydrator;
    }

    /**
     * Retrieve an hydrator.
     *
     * @param string $hydratorId The id of the hydrator.
     *
     * @return HypermediaHydratorInterface The hydrator.
     */
    protected function getHydrator($hydratorId)
    {
        if (!isset($this->hydrators[$hydratorId])) {
            throw new \LogicException(sprintf(
                'The hydrator "%s" is not defined.',
                $hydratorId
            ));
        }

        return $this->hydrators[$hydratorId];
    }
    
    /**
     * {@inheritdoc}
     */
    public function hydrate(array $hypermedia)
    {
        if (isset($hypermedia['metadata'][Constants::SERIALIZER_CONTEXT_GROUP_NAME])) {
            $hydratorId = $hypermedia['metadata'][Constants::SERIALIZER_CONTEXT_GROUP_NAME];

            return $this->getHydrator($hydratorId)->hydrate($hypermedia);
        }

        throw new \LogicException(sprintf(
            'Unable to build hypermedia object: the field "%s" is not defined in hypermedia metadata.',
            Constants::SERIALIZER_CONTEXT_GROUP_NAME
        ));
    }
}
