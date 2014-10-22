<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @author:  Nabil MANSOURI <nabil.mansouri@tessi.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Adapter;

use Pagerfanta\Adapter\AdapterInterface;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;

class HypermediaCollectionAdapter implements AdapterInterface
{
    /**
     * @var Collection $hypermediaCollection
     */
    private $hypermediaCollection;

    /**
     * Constructor.
     *
     * @param $hypermediaCollection
     */
    public function __construct(HypermediaCollection $hypermediaCollection)
    {
        $this->hypermediaCollection = $hypermediaCollection;
    }

    /**
     * {@inheritdoc }
     */
    public function getNbResults()
    {
        return $this->hypermediaCollection->countItems();
    }

    /**
     * {@inheritdoc }
     */
    public function getSlice($offset, $length)
    {
        return array(
            'metadata' => array_merge(
                $this->hypermediaCollection->getAllMetadata(),
                array(
                    'offset' => $offset,
                    'limit'  => $length
                )
            ),
            'data'     => array_slice(
                $this->hypermediaCollection->getData(),
                $offset,
                $length
            ),
            'links'    => $this->hypermediaCollection->getLinks()
        );
    }
}
