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
 * HypermediaItem
 */
class HypermediaItem extends AbstractHypermedia
{
    protected $embeddedLinks = array();

    /**
     * Constructor
     * 
     * @param array $raw
     */
    public function __construct(array $raw)
    {
        parent::__construct($raw);

        if(isset($this->links['embeddeds'])) {
            $this->embeddedLinks = $this->links['embeddeds'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $raw)
    {
        if(!isset($raw['data'])) {
            throw new NotFoundHttpException("No 'data' section found in hypermedia raw.");
        }
    
        $this->data = $raw['data'];

        return $this;
    }

    /**
     * Get embedded links
     * 
     */
    public function getEmbeddedLinks()
    {
        return $this->embeddedLinks;
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
            return $this->embeddedLinks[$name];
        }

        throw new NotFoundHttpException(sprintf("No '%s' embedded found.", $name));
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
        return isset($this->embeddedLinks[$name]);
    }

    /**
     * Follow an embedded link to retrieve new hypermedia object
     * 
     * @param  $embeddedName
     * @return HypermediaCollection
     * 
     */
    public function followEmbedded($embeddedName)
    {
        return $this->followUrl($this->getEmbeddedUrl($embeddedName));
    }
}
