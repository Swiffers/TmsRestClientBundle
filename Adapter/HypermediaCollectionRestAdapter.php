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
use Tms\Bundle\RestClientBundle\Hypermedia\Crawling\CrawlerInterface;

class HypermediaCollectionRestAdapter implements AdapterInterface
{
    private $crawler;
    private $api;
    private $path;

    /**
     * Constructor.
     *
     * @param CrawlerInterface $crawler
     * @param string           $api
     * @param string           $path
     */
    public function __construct(CrawlerInterface $crawler, $api, $path)
    {
        $this->crawler = $crawler;
        $this->api = $api;
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return $this
            ->crawler
            ->find($this->path)
            ->countItems()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        return $this->crawler
            ->go($this->api)
            ->find(
                $this->path,
                array(
                    'offset' => $offset,
                    'limit'  => $length
                )
            )
        ;
    }
}
