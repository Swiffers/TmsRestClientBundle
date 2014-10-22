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
use Tms\Bundle\RestClientBundle\Hypermedia\Crawling\CrawlerInterface;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;

class HypermediaCollectionRestAdapter implements AdapterInterface
{
    private $crawler;
    private $api;
    private $path;
    private $parameters;

    private $collection;
    private $offset;
    private $length;
    private $hash;

    /**
     * Constructor.
     *
     * @param CrawlerInterface $crawler
     * @param string           $api
     * @param string           $path
     * @param array            $paramaters
     */
    public function __construct(CrawlerInterface $crawler, $api, $path, array $parameters = array())
    {
        $this->collection = null;
        $this->offset     = null;
        $this->length     = null;
        $this->hash       = null;

        $this->crawler    = $crawler;
        $this->api        = $api;
        $this->path       = $path;
        $this->parameters = $parameters;
    }

    /**
     * Get collection.
     *
     * @return HypermediaCollection
     */
    protected function getCollection()
    {
        $hash = $this->mergeParameters();
        if ($this->hash != $hash) {
            $this->hash = $hash;
            $this->fillData();
        }

        return $this->collection;
    }

    /**
     * Merge parameters.
     *
     * @return string
     */
    protected function mergeParameters()
    {
        $this->parameters = array_merge(
            $this->parameters,
            array(
                'offset' => $this->offset,
                'length' => $this->length
            )
        );

        return md5(serialize($this->parameters));
    }

    /**
     * Fill data.
     */
    protected function fillData()
    {
        $this->collection = $this
            ->crawler
            ->go($this->api)
            ->find($this->path, $this->parameters)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return $this->getCollection()->getMetadata('totalCount');
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $this->offset = $offset;
        $this->length = $length;

        return $this->getCollection();
    }
}