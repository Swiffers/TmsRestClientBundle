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
class AbstractHypermedia
{
    protected $raw;

    public function __construct($raw)
    {
        $this->raw = $raw;
    }

    public function getMetadata($name)
    {
        if($this->hasMetadata($name)) {
            return $this->raw['metadata'][$name];
        }

        throw new NotFoundHttpException(sprintf("No '%s' metadata found.", $name));
    }

    public function hasMetadata($name)
    {
        return isset($this->raw['metadata'][$name]);
    }

    public function getAllMetadata()
    {
        return $this->raw['metadata'];
    }

    public function getData()
    {
        return $this->raw['data'];
    }

    public function getAllLinks()
    {
        return $this->raw['links'];
    }

    public function getLink($name)
    {
        if($this->hasLink($name)) {
            return $this->raw['links'][$name];
        }

        throw new NotFoundHttpException(sprintf("No '%s' link found.", $name));
    }

    public function getLinkUrl($name)
    {
        $link = $this->getLink($name);
        
        return $link['href'];
    }

    public function hasLink($name)
    {
        return isset($this->raw['links'][$name]);
    }

    public function followUrl($url)
    {
        
    }

    public function followLink($name)
    {
        $this->followUrl($this->getLinkUrl($name));
    }
}
