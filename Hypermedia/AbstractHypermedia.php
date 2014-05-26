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

/**
 * AbstractHypermedia
 */
abstract class AbstractHypermedia
{
    protected $raw;
    protected $crawler;

    /**
     * Constructor
     */
    public function __construct($raw)
    {
        $this->raw = $raw;
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
            return $this->raw['metadata'][$name];
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
        return isset($this->raw['metadata'][$name]);
    }

    /**
     * Get all metadata
     * 
     * @return array
     */
    public function getAllMetadata()
    {
        return $this->raw['metadata'];
    }

    /**
     * Get data
     * 
     * @return array
     */
    public function getData()
    {
        return $this->raw['data'];
    }

    /**
     * Get links
     * 
     * @return array
     */
    public function getAllLinks()
    {
        return $this->raw['links'];
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
            return $this->raw['links'][$name];
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
        return isset($this->raw['links'][$name]);
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
     * Follow a link name to retrieve new hypermedia object
     * 
     * @param string  $name
     * @param string  $params
     * @return mixed HypermediaCollection OR HypermediaItem
     */
    abstract public function followLink($name, $params = array());
}
