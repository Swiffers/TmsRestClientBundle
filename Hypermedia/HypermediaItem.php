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
    public function getEmbeddeds()
    {
        return $this->raw['embeddeds'];
    }

    /**
     * Get a specific embedded link
     * 
     * @param $name
     * @return string
     * 
     */
    public function getEmbedded($name)
    {
        if($this->hasEmbedded($name)) {
            return $this->raw['embeddeds'][$name];
        }

        throw new HttpNotFoundException(sprintf("No '%s' embedded found.", $name));
    }

    /**
     * Get a specific embedded link URL
     * 
     * @param $name
     * @return string URL
     * 
     */
    public function getEmbeddedUrl($name)
    {
        $link = $this->getEmbedded($name);
        
        return $link['href'];
    }

    /**
     * Check if a specific embedded link is existing
     * 
     * @param $name
     * @return boolean
     * 
     */
    public function hasEmbedded($name)
    {
        return isset($this->raw['embeddeds'][$name]);
    }

    /**
     * Follow a specific embedded link
     * 
     * @param $name
     * 
     */
    public function followEmbedded($name)
    {
       $this->followUrl($this->getEmbeddedUrl($name));
    }
}
