<?php

/**
 * 
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Adapter;

use Pagerfanta\Adapter\AdapterInterface;
use Tms\Bundle\RestClientBundle\Crawler\HypermediaCrawler;
use Tms\Bundle\RestClientBundle\Iterator\HypermediaCollectionIterator;

class HypermediaCollectionRestAdapter implements AdapterInterface
{
    private $crawler;
    private $collectionName;

    /**
     * Constructor.
     *
     * @param HypermediaCrawler $crawler
     * @param string $collectionName
     */
    public function __construct(HypermediaCrawler $crawler, $collectionName)
    {
        $this->crawler = $crawler;
        $this->collectionName = $collectionName;
    }

    /**
     * {@inheritdoc }
     */
    public function getNbResults()
    {
        return $this
            ->crawler
            ->findAll($this->collectionName)
            ->countItems()
        ;
    }

    /**
     * {@inheritdoc }
     */
    public function getSlice($offset, $length)
    {
        return $this->crawler->findAll(
            $this->collectionName,
            array(
                'offset' => $offset,
                'limit'  => $length
            )
        );
    }
}
