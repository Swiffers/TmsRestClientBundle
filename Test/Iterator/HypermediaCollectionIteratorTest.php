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
    private $raw = array(
        'data' => array('a', 'b', 'c', 'd', 'e')
    );

    public function testCurrent()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->raw)
        );

        $this->assertEquals('a', $iterator->current());
    }

    public function testNext()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->raw)
        );

        $iterator->next();
        $this->assertEquals('b', $iterator->current());
        
        $iterator
            ->next() // c
            ->next() // d
        ;
        $this->assertEquals('d', $iterator->current());

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $iterator
            ->next() // e
            ->next() // Error
        ;
        $iterator->current(); // NotFoundHttpException?
    }

    public function testPrevious()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->raw)
        );

        $iterator
            ->next() // b
            ->next() // c
        ;

        $iterator->previous(); // b ?
        $this->assertEquals('b', $iterator->current());

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $iterator
            ->previous() // a
            ->previous() // Error
        ;
        $iterator->current(); // NotFoundHttpException?
    }

    public function testKey()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->raw)
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
            new HypermediaCollection($this->raw)
        );

        $iterator
            ->next() // b
            ->next() // c
        ;
        $iterator->rewind(); // a?

        $this->assertEquals(0, $iterator->key());
    }

    public function testUnwind()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->raw)
        );

        $iterator
            ->next() // b
            ->next() // c
        ;
        $iterator->unwind();

        $this->assertEquals(count($this->raw['data']), $iterator->key());
    }

    public function testValid()
    {
        $iterator = new HypermediaCollectionIterator(
            new HypermediaCollection($this->raw)
        );

        $iterator
            ->next() // b
            ->next() // c
            ->next() // d
            ->next() // e
        ;

        $this->assertEquals(true, $iterator->valid());
    }

    public function testLoop()
    {
        $hypermediaCollection = new HypermediaCollection($this->raw);
        $iterator = new HypermediaCollectionIterator($hypermediaCollection);

        foreach($hypermediaCollection as $item) {
            $this->assertEquals($item, $iterator->current());
            $iterator->next();
        }
    }
}
