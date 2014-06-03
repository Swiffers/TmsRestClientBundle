<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Test\Iterator;

use Tms\Bundle\RestClientBundle\Factory\HypermediaFactory;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem;
use Tms\Bundle\RestBundle\Formatter\AbstractHypermediaFormatter;

/**
 * HypermediaCollectionIteratorTest
 */
class HypermediaFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $rawCollection = array(
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
            )
        ),
        'links' => array()
    );
    
    private $rawItem = array(
        'metadata'  => array(
            'serializerContextGroup' => AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_ITEM
        ),
        'data' => array('a', 'b', 'c', 'd', 'e'),
        'links' => array()
    );

    private $rawErrorWithoutLinks = array(
        'metadata'  => array(
            'serializerContextGroup' => AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_ITEM
        ),
        'data' => array('a', 'b', 'c', 'd', 'e')
    );

    private $rawErrorWithoutSerializer = array(
        'metadata'  => array(),
        'data'      => array('a', 'b', 'c', 'd', 'e'),
        'links'     => array()
    );

    public function testHypermediaCollectionFactory()
    {
        $hypermediaCollection = HypermediaFactory::build($this->rawCollection);

        // Test class built by factory
        $this->assertEquals(get_class($hypermediaCollection), 'Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection');

        $this->assertEquals($this->rawCollection['metadata'], $hypermediaCollection->getAllMetadata());
        $this->assertEquals($this->rawCollection['links'], $hypermediaCollection->getAllLinks());

        $i = 0;
        foreach($hypermediaCollection as $item) {
            $this->assertEquals($this->rawCollection['data'][$i]['data'], $item->getData());
            $this->assertEquals(get_class($item), 'Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem');
            $i++;
        }
    }

    public function testHypermediaItemFactory()
    {
        $hypermediaItem = HypermediaFactory::build($this->rawItem);

        // Test class built by factory
        $this->assertEquals(get_class($hypermediaItem), 'Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem');

        $this->assertEquals($this->rawItem['metadata'], $hypermediaItem->getAllMetadata());
        $this->assertEquals($this->rawItem['data'], $hypermediaItem->getData());
        $this->assertEquals($this->rawItem['links'], $hypermediaItem->getAllLinks());
    }

    public function testHypermediaErrorFactory()
    {
        $this->setExpectedException('Exception');
        $hypermediaError = HypermediaFactory::build($this->rawErrorWithoutSerializer);

        $this->setExpectedException('Symfony\Component\HttpKernel\Exception\NotFoundHttpException');
        $hypermediaError = HypermediaFactory::build($this->$rawErrorWithoutLinks);
    }
}
