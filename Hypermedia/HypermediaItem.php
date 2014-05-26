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
        $link = $this->getEmbedded($name);
        
        return $link['href'];
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
     * @param string $name
     * @param string  $params
     * 
     */
    public function followEmbedded($name, $params = array())
    {
        return new HypermediaCollection(
            $this->crawler->get($this->getEmbeddedUrl($name), $params)
        );
    }

    /**
     * Follow a link to retrieve new hypermedia object
     * 
     * @param string $name
     * @param string  $params
     * @return HypermediaCollection
     */
    public function followLink($name, $params = array())
    {
        return new HypermediaItem(
            $this->crawler->get($this->getLinkUrl($name), $params)
        );
    }
}
