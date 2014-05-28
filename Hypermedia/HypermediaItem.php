<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Hypermedia;

/**
 * HypermediaItem
 */
class HypermediaItem extends AbstractHypermedia
{
    /**
     * Get embedded links
     * 
     */
    public function getEmbeddedLinks()
    {
        return $this->raw['links']['embeddeds'];
    }

    /**
     * Get a specific embedded link
     * 
     * @param string $name
     * @return string
     * 
     */
    public function getEmbeddedLink($name)
    {
        if($this->hasEmbedded($name)) {
            return $this->raw['links']['embeddeds'][$name];
        }

        throw new HttpNotFoundException(sprintf("No '%s' embedded found.", $name));
    }

    /**
     * Get a specific embedded link URL
     * 
     * @param string $name
     * @return string URL
     * 
     */
    public function getEmbeddedUrl($name)
    {
        $link = $this->getEmbeddedLink($name);
        
        return $link['href'];
    }

    /**
     * Get a specific embedded link query array
     * 
     * @param string $name
     * @return array
     */
    public function getEmbeddedUrlQueryArray($name)
    {
        $queryString = parse_url($this->getEmbeddedUrl($name), PHP_URL_QUERY);
        parse_str($queryString, $queryArray);

        return $queryArray;
    }

    /**
     * Get a specific embedded link path
     * 
     * @param string $name
     * @return string
     */
    public function getEmbeddedUrlPath($name)
    {
        return parse_url($this->getEmbeddedUrl($name), PHP_URL_PATH);
    }

    /**
     * Check if a specific embedded link exists
     * 
     * @param string $name
     * @return boolean
     * 
     */
    public function hasEmbedded($name)
    {
        return isset($this->raw['links']['embeddeds'][$name]);
    }

    /**
     * Follow an embedded link to retrieve new hypermedia object
     * 
     * @param string $currentName
     * @param string $embeddedName
     * @return HypermediaCollection
     * 
     */
    public function followEmbedded($currentName, $embeddedName)
    {
        // TODO : Get ID in path
        $explodedPath = explode('/', $this->getEmbeddedUrlPath($embeddedName));
        $id = $explodedPath[count($explodedPath)-1];

        return $this
            ->crawlerHandler
            ->guessCrawler($this->getEmbeddedUrlPath($embeddedName))
            ->findAll(array($currentName => $id))
        ;
    }

    /**
     * Follow a link to retrieve new hypermedia object
     * 
     * @param string $name
     * @return HypermediaItem
     */
    public function followLink($name)
    {
        $explodedPath = explode('/', $this->getLinkUrlPath($name));
        $id = $explodedPath[count($explodedPath)-1];

        return $this
            ->crawlerHandler
            ->guessCrawler($this->getLinkUrlPath($name))
            ->find($id)
        ;
    }
}
