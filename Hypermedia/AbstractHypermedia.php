<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Hypermedia;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tms\Bundle\RestClientBundle\Crawler\HypermediaCrawlerHandler;

/**
 * AbstractHypermedia
 */
abstract class AbstractHypermedia
{
    protected $crawlerHandler;
    protected $metadata;
    protected $data;
    protected $links;

    /**
     * Constructor
     * 
     * @param HypermediaCrawlerHandler $crawlerHandler
     * @param array $raw
     */
    public function __construct(HypermediaCrawlerHandler $crawlerHandler, array $raw)
    {
        $this->crawlerHandler = $crawlerHandler;

        try {
            $this->normalize($raw);
        } catch(\Exception $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * Normalize array to Hypermedia object
     * 
     * @param array $raw
     */
    public function normalize(array $raw)
    {
        $this->setMetadata($raw);
        $this->setData($raw);
        $this->setLinks($raw);
    }

    /**
     * Set metadata
     * 
     * @param array $raw
     */
    public function setMetadata(array $raw)
    {
        if(!isset($raw['metadata'])) {
            throw new HttpNotFoundException("No 'metadata' section found in hypermedia raw.");
        }
    
        $this->metadata = $raw['metadata'];
    }

    /**
     * Set data
     * 
     * @param array $raw
     */
    public function setData(array $raw)
    {
        if(!isset($raw['data'])) {
            throw new HttpNotFoundException("No 'data' section found in hypermedia raw.");
        }
    
        $this->data = $raw['data'];
    }

    /**
     * Set links
     * 
     * @param array $raw
     */
    public function setLinks(array $raw)
    {
        if(!isset($raw['links'])) {
            throw new HttpNotFoundException("No 'links' section found in hypermedia raw.");
        }
    
        $this->links = $raw['links'];
    }
    
    /**
     * Get a specific metadata
     * 
     * @param string $name
     * @return mixed $metadata
     */
    public function getMetadata($name)
    {
        if($this->hasMetadata($name)) {
            return $this->metadata[$name];
        }

        throw new NotFoundHttpException(sprintf("No '%s' metadata found.", $name));
    }

    /**
     * Check if a specific metadata exists
     * 
     * @param string $name
     * @return boolean
     */
    public function hasMetadata($name)
    {
        return isset($this->metadata[$name]);
    }

    /**
     * Get all metadata
     * 
     * @return array
     */
    public function getAllMetadata()
    {
        return $this->metadata;
    }

    /**
     * Get data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get links
     * 
     * @return array
     */
    public function getAllLinks()
    {
        return $this->links;
    }

    /**
     * Get a specific link
     * 
     * @param string $name
     * @return array
     */
    public function getLink($name)
    {
        if($this->hasLink($name)) {
            return $this->links[$name];
        }

        throw new NotFoundHttpException(sprintf("No '%s' link found.", $name));
    }

    /**
     * Check if a specific link exists
     * 
     * @param string $name
     * @return boolean
     */
    public function hasLink($name)
    {
        return isset($this->links[$name]);
    }

    /**
     * Get a link URL
     * 
     * @param string $name
     * @return string URL
     */
    public function getLinkUrl($name)
    {
        $link = $this->getLink($name);
        
        return $link['href'];
    }

    /**
     * Get a link query array
     * 
     * @param string $name
     * @return array
     */
    public function getLinkUrlQueryArray($name)
    {
        $queryString = parse_url($this->getLinkUrl($name), PHP_URL_QUERY);
        parse_str($queryString, $queryArray);

        return $queryArray;
    }

    /**
     * Get a link path
     * 
     * @param string $name
     * @return string
     */
    public function getLinkUrlPath($name)
    {
        return parse_url($this->getLinkUrl($name), PHP_URL_PATH);
    }
    

    /**
     * Follow a link name to retrieve new hypermedia object
     * 
     * @param string  $name
     * @return mixed HypermediaCollection OR HypermediaItem
     */
    abstract public function followLink($name);
}
