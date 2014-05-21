<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Iterator;

use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * HypermediaCollectionIterator
 */
class HypermediaCollectionIterator implements \Iterator
{
    private $cursor = 0;
    private $collection;

    function __construct(HypermediaCollection $collection)
    {
        $this->cursor = 0;
        $this->collection = $collection;
    }

    public function current()
    {
        $data = $this->collection->getData();
 
        return $data[$this->cursor];
    }

    public function key()
    {
        return $this->cursor;
    }

    public function hasNext()
    {
        if ($this->cursor+1 >= count($this->collection->getData())) {
            return false;
        }

        return true;
    }

    public function hasPrevious()
    {
        if ($this->cursor-1 < 0) {
            return false;
        }

        return true;
    }
 
    public function next()
    {
        if(!$this->hasNext()) {
            throw new NotFoundHttpException();
        }
        
        $data = $this->collection->getData();

        return $data[++$this->cursor];
    }

    public function previous()
    {
        if (!$this->hasPrevious()) {
            throw new NotFoundHttpException();
        }

        $data = $this->collection->getData();
 
        return $data[--$this->cursor];
    }

    public function rewind()
    {
        $this->cursor = 0;
    }

    public function valid()
    {
        return isset($this->collection[$this->cursor]);
    }

}
