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

        return $data[$this->key()];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return ($this->cursor % $this->hypermediaCollection->getMetadata('pageCount'));
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        if ($this->cursor < $this->hypermediaCollection->getMetadata('totalCount')) {
            $this->cursor++;
        }

        if (0 !== $this->cursor && 0 === $this->cursor % $this->hypermediaCollection->getMetadata('pageCount')) {
            if ($this->hypermediaCollection->hasNextPage()) {
                $this->hypermediaCollection = $this->hypermediaCollection->nextPage();
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        if ($this->hypermediaCollection->hasPreviousPage()) {
            $this->hypermediaCollection = $this->hypermediaCollection->firstPage();
        }

        $this->cursor = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $data = $this->hypermediaCollection->getData();

        return (
            $this->cursor < $this->hypermediaCollection->getMetadata('totalCount') &&
            isset($data[$this->key()])
        );
    }
}
