<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia;

use Tms\Bundle\RestClientBundle\Iterator\HypermediaCollectionIterator;

/**
 * HypermediaCollection.
 *
 * @author Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
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
}
