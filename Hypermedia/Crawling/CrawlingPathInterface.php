<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Crawling;

/**
 * CrawlingPathInterface is the interface that a class must implement
 * to be used as a crawling path that the crawler can follow.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
interface CrawlingPathInterface
{
    /**
     * Set the crawler.
     *
     * @param CrawlerInterface $crawler The crawler.
     */
    function setCrawler(CrawlerInterface $crawler);

    /**
     * Get the endpoint root.
     *
     * @return string The endpoint root.
     */
    function getEndpointRoot();

    /**
     * Whether or not a path is matching with this crawling path.
     *
     * @param string $path The path to match.
     *
     * @return boolean True if the path match, false otherwise.
     */
    function matchPath($path);

    /**
     * Find one.
     *
     * @param string  $path    The path.
     * @param string  $param   The path parameter.
     * @param array   $headers The headers.
     * @param boolean $noCache To force the request without check if a cache response exist.
     *
     * @return \Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem The hypermedia.
     */
    function findOne($path, $param, array $headers = array(), $noCache = false);

    /**
     * Find.
     *
     * @param string  $path    The path.
     * @param array   $params  The query parameters.
     * @param array   $headers The headers.
     * @param boolean $noCache To force the request without check if a cache response exist.
     *
     * @return \Tms\Bundle\RestClientBundle\Hypermedia\AbstractHypermedia The hypermedia.
     */
    function find($path, array $params = array(), array $headers = array(), $noCache = false);

    /**
     * Get the info of the path.
     *
     * @param string $path    The path.
     * @param array  $headers The headers.
     *
     * @return \Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem The hypermedia.
     */
    function getPathInfo($path, array $headers = array());

    /**
     * Crawl an URL.
     *
     * @param string  $path    The path.
     * @param array   $params  The query parameters.
     * @param array   $headers The headers.
     * @param boolean $noCache To force the request without check if a cache response exist.
     *
     * @return \Tms\Bundle\RestClientBundle\Hypermedia\AbstractHypermedia The hypermedia.
     */
    function crawl($path, array $params = array(), array $headers = array(), $noCache = false);

    /**
     * Execute an action.
     *
     * @param string $path    The path.
     * @param string $method  The HTTP method.
     * @param array  $params  The query parameters.
     * @param array  $headers The headers.
     *
     * @return mixed The return of the execution.
     */
    function execute($path, $method, array $params = array(), array $headers = array());
}
