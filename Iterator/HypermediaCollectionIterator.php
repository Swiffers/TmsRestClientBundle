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
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * HypermediaCollectionIterator
 */
class HypermediaCollectionIterator implements \Iterator
{
    private $cursor = 0;
    private $hypermediaCollection;

    /**
     * Constructor
     */
    function __construct(HypermediaCollection $hypermediaCollection)
    {
        $this->cursor = 0;
        $this->hypermediaCollection = $hypermediaCollection;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $data = $this->hypermediaCollection->getData();
 
        if(!$this->valid()) {
            throw new NotFoundHttpException();
        }

        return $data[$this->cursor];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->cursor;
    }

    /**
     * {@inheritdoc}
     */
    public function hasNext()
    {
        if ($this->cursor+2 > count($this->hypermediaCollection->getData())) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPrevious()
    {
        if ($this->cursor-1 < 0) {
            return false;
        }

        return true;
    }
 
    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->cursor++;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function previous()
    {
        $this->cursor--;
 
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->cursor = 0;
    }

    /**
     * Unwind the cursor to the last index
     */
    public function unwind()
    {
        $this->cursor = count($this->hypermediaCollection->getData());
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $data = $this->hypermediaCollection->getData();

        return isset($data[$this->cursor]);
    }

}
