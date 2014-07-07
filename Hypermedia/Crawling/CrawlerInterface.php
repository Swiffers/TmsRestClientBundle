<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Crawling;

/**
 * CrawlerInterface is the interface a class should implement
 * to be used as a crawler in a hypermedia context.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
interface CrawlerInterface
{
    /**
     * Go on a crawling path.
     *
     * @param string $crawlingPathId The id of the crawling path.
     *
     * @return CrawlingPathInterface The crawling path.
     */
    function go($crawlingPathId);

	/**
     * Crawl to an URL.
     *
     * @param string $url The url to crawl to.
     * @param array  $params The query parameters.
     *
     * @return \Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection|\Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem The retrieved hypermedia.
     */
    function crawl($url, array $params = array());

    /**
     * Execute an action.
     *
     * @param string $url    The URL.
     * @param string $method The HTTP method.
     * @param array  $params The query parameters.
     *
     * @return mixed The return of the execution.
     */
    function execute($url, $method, array $params = array());
}
