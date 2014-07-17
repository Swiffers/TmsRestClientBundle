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
use Tms\Bundle\RestBundle\Formatter\AbstractHypermediaFormatter;
use Tms\Bundle\RestClientBundle\Factory\HypermediaFactory;

/**
 * HypermediaCollectionIteratorTest
 */
class HypermediaCollectionIteratorTest extends \PHPUnit_Framework_TestCase
{
    private $raw = array(
        'metadata'  => array(
            'serializerContextGroup' => AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_COLLECTION,
        ),
        'data' => array(
            array(
                'metadata'  => array(
                    'serializerContextGroup' => AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_ITEM,
                ),
                'data' => 'a',
                'links' => array()
            ),
            array(
                'metadata'  => array(
                    'serializerContextGroup' => AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_ITEM,
                ),
                'data' => 'b',
                'links' => array()
            ),
            array(
                'metadata'  => array(
                    'serializerContextGroup' => AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_ITEM,
                ),
                'data' => 'c',
                'links' => array()
            ),
        ),
        'links' => array()
    );

    public function testCurrent()
    {
        $hypermediaCollection = HypermediaFactory::build($this->raw);
        $iterator = new HypermediaCollectionIterator($hypermediaCollection);

        $this->assertEquals('a', $iterator->current()->getData());
    }

    public function testNext()
    {
        $hypermediaCollection = HypermediaFactory::build($this->raw);
        $iterator = new HypermediaCollectionIterator($hypermediaCollection);

        $iterator->next();
        $this->assertEquals('b', $iterator->current()->getData());

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $iterator
            ->next() // c
            ->next() // error
        ;
        $iterator->current(); // NotFoundHttpException?
    }

    public function testPrevious()
    {
        $hypermediaCollection = HypermediaFactory::build($this->raw);
        $iterator = new HypermediaCollectionIterator($hypermediaCollection);

        $iterator
            ->next() // b
            ->next() // c
        ;

        $iterator->previous(); // b ?
        $this->assertEquals('b', $iterator->current()->getData());

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $iterator
            ->previous() // a
            ->previous() // Error
        ;
        $iterator->current(); // NotFoundHttpException?
    }

    public function testKey()
    {
        $hypermediaCollection = HypermediaFactory::build($this->raw);
        $iterator = new HypermediaCollectionIterator($hypermediaCollection);

        $this->assertEquals(0, $iterator->key());

        $iterator->next(); // b
        $this->assertEquals(1, $iterator->key());

        $iterator->next(); // c
        $this->assertEquals(2, $iterator->key());
    }

    public function testRewind()
    {
        $hypermediaCollection = HypermediaFactory::build($this->raw);
        $iterator = new HypermediaCollectionIterator($hypermediaCollection);

        $iterator
            ->next() // b
            ->next() // c
        ;
        $iterator->rewind(); // a?

        $this->assertEquals(0, $iterator->key());
    }

    public function testUnwind()
    {
        $hypermediaCollection = HypermediaFactory::build($this->raw);
        $iterator = new HypermediaCollectionIterator($hypermediaCollection);

        $iterator
            ->next() // b
            ->next() // c
        ;
        $iterator->unwind();

        $this->assertEquals(count($this->raw['data']), $iterator->key());
    }

    public function testValid()
    {
        $hypermediaCollection = HypermediaFactory::build($this->raw);
        $iterator = new HypermediaCollectionIterator($hypermediaCollection);

        $iterator
            ->next() // b
            ->next() // c
            ->next() // d
            ->next() // e
        ;

        $this->assertEquals(false, $iterator->valid());
    }

    public function testLoop()
    {
        $hypermediaCollection = HypermediaFactory::build($this->raw);
        $iterator = new HypermediaCollectionIterator($hypermediaCollection);

        foreach($hypermediaCollection as $item) {
            $this->assertEquals($item->getData(), $iterator->current()->getData());
            $iterator->next();
        }
    }
}
