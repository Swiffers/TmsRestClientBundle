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
    public function getEmbeddeds()
    {
        return $this->raw['embeddeds'];
    }

    public function getEmbedded($name)
    {
        if($this->hasEmbedded($name)) {
            return $this->raw['embeddeds'][$name];
        }

        throw new HttpNotFoundException(sprintf("No '%s' embedded found.", $name));
    }

    public function getEmbeddedUrl($name)
    {
        $link = $this->getEmbedded($name);
        
        return $link['href'];
    }

    public function hasEmbedded($name)
    {
        return isset($this->raw['embeddeds'][$name]);
    }

    public function followEmbedded($name)
    {
       $this->followUrl($this->getEmbeddedUrl($name));
    }
}
