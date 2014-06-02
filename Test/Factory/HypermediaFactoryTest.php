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
            'page'       => 1,
            'pageCount'  => 10,
            'totalCount' => 20,
            'limit'      => 10,
            'offset'     => 0
        ),
        'data' => array('a', 'b', 'c', 'd', 'e'),
        'links' => array(
            'self' => array(
                'rel'  => 'self',
                'href' => 'http://www.google.fr'
            )
        )
    );
    
    private $rawItem = array(
        'metadata'  => array(
            'serializerContextGroup' => AbstractHypermediaFormatter::SERIALIZER_CONTEXT_GROUP_ITEM
        ),
        'data' => array('a', 'b', 'c', 'd', 'e'),
        'links' => array(
            'self' => array(
                'rel'  => 'self',
                'href' => 'http://www.google.fr'
            )
        )
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
        $this->assertEquals($this->rawCollection['data'], $hypermediaCollection->getData());
        $this->assertEquals($this->rawCollection['links'], $hypermediaCollection->getAllLinks());
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
