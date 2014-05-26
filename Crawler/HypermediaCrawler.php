<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Crawler;


/**
 * HypermediaCrawler
 */
class HypermediaCrawler
{
    protected $apiClient;

    /**
     * Constructor
     */
    public function __construct($apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Get API REST raw
     * 
     * @param string $url
     * @param array $params
     * 
     * @return array
     */
    public function get($url, $params = array())
    {
        return $this->apiClient->get($url, $params);
    }

    /**
     * Find an HypermediaItem
     * 
     * @param array $params
     * @return HypermediaItem
     */
    public function findOneBy($params = array())
    {
        return $this->get($this->apiClient->getEndpointRoot(), $params);
    }

    /**
     * Find an HypermediaCollection
     * 
     * @param array $params
     * @return HypermediaCollection
     */
    public function findAll($params = array())
    {
        return $this->get($this->apiClient->getEndpointRoot(), $params);
    }
}
