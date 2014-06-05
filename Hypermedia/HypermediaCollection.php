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
use Tms\Bundle\RestClientBundle\Factory\HypermediaFactory;

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
     * {@inheritdoc}
     */
    public function setData(array $raw)
    {
        if(!isset($raw['data'])) {
            throw new NotFoundHttpException("No 'data' section found in hypermedia raw.");
        }

        // Build a collection of HypermediaItem
        foreach($raw['data'] as $item)
        {
            $this->data[] = HypermediaFactory::build($item);
        }

        return $this;
    }

    /**
     * Follow the nextPage link
     * 
     * @return HypermediaCollection
     */
    public function nextPage()
    {
        return $this->followLink('nextPage');
    }

    /**
     * Follow the previousPage link
     * 
     * @return HypermediaCollection
     */
    public function previousPage()
    {
        return $this->followLink('previousPage');
    }
    
    /**
     * Check if the collection has next page
     * 
     * @return boolean
     */
    public function hasNextPage()
    {
        return $this->getLinkUrl('nextPage') != '';
    }

    /**
     * Check if the collection has previous page
     * 
     * @return boolean
     */
    public function hasPreviousPage()
    {
        return $this->getLinkUrl('previousPage') != '';
    }

    /**
     * Follow the lastPage link
     * 
     * @return HypermediaCollection
     */
    public function lastPage()
    {
        return $this->followLink('lastPage');
    }

    /**
     * Follow the firstPage link
     * 
     * @return HypermediaCollection
     */
    public function firstPage()
    {
        return $this->followLink('firstPage');
    }

    /**
     * Follow a specific page number link
     * 
     * @param integer $page
     * @return HypermediaCollection
     */
    public function page($page)
    {
        return $this->followUrl($this->getLinkUrl('self'), array(
            'page' => $page
        ));
    }

    /**
     * Count collection items
     * 
     * @return integer
     */
    public function countItems()
    {
        return count($this->data);
    }
}
