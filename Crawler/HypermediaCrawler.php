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
use Tms\Bundle\RestClientBundle\Factory\HypermediaFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * HypermediaCrawler
 */
class HypermediaCrawler
{
    protected $apiClient;

    /**
     * Constructor
     */
    public function __construct(RestApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
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
     * Get crawler embeddeds API paths
     * 
     * @param $id
     * @param $embeddedName
     * 
     * @return string
     */
    public function getEmbeddedPath($id, $embeddedName)
    {
        if(!isset($this->crawlerConfiguration['embeddeds'][$embeddedName])) {
            throw new NotFoundHttpException(sprintf(
                "No embedded named '%s' found.", $embeddedName
            ));
        }

        return str_replace(
            "{id}",
            $id,
            $this->crawlerConfiguration['embeddeds'][$embeddedName]['path']
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
     * @param string $path
     * @return boolean
     */
    public function matchPath($path)
    {
        $collectionPath = sprintf("/app_dev.php/api/rest%s", $this->getCollectionPath());
        if($collectionPath == $path) {
            return true;
        }

        
        // TODO : Get ID in path
        if(!preg_match('/([0-9]+)/', $path, $matches)) {
            return false;
        }
        $id = intval($matches[0]);

        var_dump($itemPath); die;
        // If it is not a collection, is it an item ?
        $itemPath = sprintf("/app_dev.php/api/rest%s", $this->getItemPath($id));
        if($path == $itemPath) {
            return true;
        }

        // Is it an embedded ?
        foreach($this->crawlerConfiguration['embeddeds'] as $embeddedName => $embedded)
        {
            $embeddedPath = sprintf("/app_dev.php/api/rest%s", $this->getEmbeddedPath($id, $embeddedName));
            if($path == $embeddedPath) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find an HypermediaItem
     * 
     * @param string $name
     * @param string $key
     * 
     * @return HypermediaItem
     */
    public function find($name, $key)
    {
        $path = sprintf("%s/%s", $name, $key);

        return $this->crawle($path);
    }

    /**
     * Find an HypermediaCollection
     * 
     * param string $name
     * @param array $params
     * 
     * @return HypermediaCollection
     */
    public function findAll($name, array $params = array())
    {
        $path = sprintf("%s", $name);

        return $this->crawle($path, $params);
    }

    /**
     * Crawle an URL (absolute or relative)
     * 
     * @param string  $path
     * @param array   $params
     * @param boolean $absolutePath
     * 
     * @return HypermediaCollection | HypermediaItem
     */
    public function crawle($path, array $params = array(), $absolutePath = false)
    {
        /* Build an HypermediaCollection or HypermediaItem
         * according to the metadata type of the API raw
         */
        $hypermedia = HypermediaFactory::build(
            $this
                ->apiClient
                ->get($path, $params, array(), false, $absolutePath)
                ->getContent(true)
        );

        // Useful to navigate in hypermedia by following links
        $hypermedia->setCrawler($this);

        return $hypermedia;
    }
}
