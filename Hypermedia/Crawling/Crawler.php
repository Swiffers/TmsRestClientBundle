<?php

namespace Tms\Bundle\RestClientBundle\Crawler;

use Da\ApiClientBundle\Http\Rest\RestApiClientInterface;
use Da\ApiClientBundle\Http\Response;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;
use Tms\Bundle\RestClientBundle\Factory\HypermediaFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Crawler is the class that allow to crawl in a hypermedia context.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class Crawler
{
    /**
     * The crawling paths.
     *
     * @var array
     */
    protected $crawlingPaths = array();

    /**
     * Set a crawling path.
     *
     * @param $id           string                The id of the crawling path.
     * @param $crawlingPath CrawlingPathInterface The crawling path.
     */
    public function setCrawlingPath($id, CrawlingPathInterface $crawlingPath)
    {
        $this->crawlingPaths[$id] = $crawlingPath;
    }

    /**
     * Go on a crawling path.
     *
     * @param $crawlingPathId string The id of the crawling path.
     *
     * @return CrawlingPathInterface The crawling path.
     */
    public function go($crawlingPathId)
    {
        if (!isset($this->crawlingPaths[$id])) {
            throw new \LogicException(sprintf(
                'The crawling path "%s" is not defined.',
                $id
            ));
        }

        return $this->crawlingPaths[$id];
    }

    /**
     * Find a crawling path.
     *
     * @param $crawlingPathId string The id of the crawling path.
     *
     * @return CrawlingPathInterface The crawling path.
     */
    public function findPath($url)
    {
        foreach ($this->crawlingPaths as $crawlingPath) {
            $crawlingPath->;
        }
    }
}
