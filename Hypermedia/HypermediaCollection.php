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
 * HypermediaCollection
 */
class HypermediaCollection extends AbstractHypermedia implements \IteratorAggregate
{
    public function getIterator()
    {
        return new HypermediaCollectionIterator($this->getData());
    }

    public function nextPage()
    {
        $this->followLink('next');
    }

    public function previousPage()
    {
        $this->followLink('previous');
    }

    public function lastPage()
    {
        $this->followLink('lasts');
    }

    public function firstPage()
    {
        $this->followLink('first');
    }

    public function page($page)
    {
        
    }
}
