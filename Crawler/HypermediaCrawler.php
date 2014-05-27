<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Crawler;

use Da\ApiClientBundle\Http\Rest\RestApiClientInterface;
use Da\ApiClientBundle\Http\Response;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;

/**
 * HypermediaCrawler
 */
class HypermediaCrawler
{
    protected $apiClient;
    protected $crawlerName;
    protected $crawlerConfiguration;
    protected $crawlerHandler;

    /**
     * Constructor
     */
    public function __construct(RestApiClientInterface $apiClient, $crawlerName, array $crawlerConfiguration = null)
    {
        $this->apiClient = $apiClient;
        $this->crawlerName = $crawlerName;
        $this->crawlerConfiguration = $crawlerConfiguration;
    }
    
    public function setCrawlerHandler(HypermediaCrawlerHandler $crawlerHandler)
    {
        $this->crawlerHandler = $crawlerHandler;

        return $this;
    }

    /**
     * Get crawler item API path
     * 
     * @param $id
     * @return string
     */
    public function getItemPath($id)
    {
        if(!isset($this->crawlerConfiguration)) {
            return sprintf("%s/%s", $this->crawlerName, $id);
        }

        return str_replace(
            "{id}",
            $id,
            $this->crawlerConfiguration['item']['path']
        );
    }

    /**
     * Get crawler collection API path
     * 
     * @return string
     */
    public function getCollectionPath()
    {
        if(!isset($this->crawlerConfiguration)) {
            return $this->crawlerName;
        }

        return $this->crawlerConfiguration['collection']['path'];
    }

    /**
     * Check if a path is matching with item OR collection path of the crawler
     * 
     * @return boolean
     */
    public function matchPath($path)
    {
        $collectionPath = sprintf("/app_dev.php/api/rest%s", $this->getCollectionPath());
        if($collectionPath == $path) {
            return true;
        }

        // TODO : check if item path match with regex
        $explodedPath = explode('/', $path);
        $id = $explodedPath[count($explodedPath)-1];

        $itemPath = sprintf("/app_dev.php/api/rest%s", $this->getItemPath($id));

        if($path == $itemPath) {
            return true;
        }

        return false;
    }

    /**
     * Find an HypermediaItem
     * 
     * @param array $id
     * @return HypermediaItem
     */
    public function find($id)
    {
        return new HypermediaItem($this->crawlerHandler, $this
            ->apiClient
            ->get($this->getItemPath($id))
            ->getContent(true)
        );
    }

    /**
     * Find an HypermediaCollection
     * 
     * @param array $params
     * @return HypermediaCollection
     */
    public function findAll(array $params = array())
    {
        return new HypermediaCollection($this->crawlerHandler, $this
            ->apiClient
            ->get($this->getCollectionPath(), $params)
            ->getContent(true)
       );
    }
}
