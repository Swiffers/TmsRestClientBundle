<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Hypermedia;

use Tms\Bundle\RestClientBundle\Iterator\HypermediaCollectionIterator;

/**
 * HypermediaCollection
 */
class HypermediaCollection extends AbstractHypermedia implements \IteratorAggregate
{
    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new HypermediaCollectionIterator($this);
    }

    /**
     * Follow the nextPage link
     * 
     */
    public function nextPage()
    {
        return $this->followLink('nextPage');
    }

    /**
     * Follow the previousPage link
     * 
     */
    public function previousPage()
    {
        return $this->followLink('previousPage');
    }

    /**
     * Follow the lastPage link
     * 
     */
    public function lastPage()
    {
        return $this->followLink('lastPage');
    }

    /**
     * Follow the firstPage link
     * 
     */
    public function firstPage()
    {
        return $this->followLink('firstPage');
    }

    /**
     * Follow a specific page number link
     * 
     * @param integer $page
     * 
     */
    public function page($page)
    {
        return $this
            ->crawlerHandler
            ->guessCrawler($this->getLinkUrlPath('self'))
            ->findAll(array_merge(
                $this->getLinkUrlQueryArray('self'),
                array('page' => $page)
            ))
        ;
    }

    /**
     * Follow an url to retrieve new hypermedia object
     * 
     * @param string  $name
     * @return HypermediaCollection
     */
    public function followLink($name)
    {
        if($this->getLinkType($name) == 'collection') {
            return $this
                ->crawlerHandler
                ->guessCrawler($this->getLinkUrlPath($name))
                ->findAll($this->getLinkUrlQueryArray($name))
            ;
        }

        return $this
            ->crawlerHandler
            ->guessCrawler($this->getLinkUrlPath($name))
            ->find($this->getLinkUrlQueryArray($name))
        ;
    }
}
