<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Test\Iterator;

use Tms\Bundle\RestClientBundle\Iterator\HypermediaCollectionIterator;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;

/**
 * HypermediaCollectionIteratorTest
 */
class HypermediaCollectionIteratorTest extends \PHPUnit_Framework_TestCase
{
    private $collection = array(
        'data' => array('a', 'b', 'c', 'd', 'e')
    );

    public function testCurrent()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->collection)
        );

        $this->assertEquals('a', $iterator->current());
        
        $iterator->next(); // b
        $this->assertEquals('b', $iterator->current());
    }

    public function testNext()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->collection)
        );

        $this->assertEquals('b', $iterator->next());
        
        $iterator->next(); // c
        $iterator->next(); // d
        $iterator->next(); // e

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $iterator->next(); // throw new NotFoundHttpException() ?
            
    }

    public function testPrevious()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->collection)
        );
        $iterator->next(); // b
        $iterator->next(); // c

        $this->assertEquals('b', $iterator->previous());
    }

    public function testKey()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->collection)
        );

        $this->assertEquals(0, $iterator->key());

        $iterator->next(); // b
        $this->assertEquals(1, $iterator->key());

        $iterator->next(); // c
        $this->assertEquals(2, $iterator->key());
    }

    public function testRewind()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->collection)
        );
        $iterator->next(); // b
        $iterator->next(); // c
        $iterator->rewind(); // a?

        $this->assertEquals(0, $iterator->key());
    }
}
