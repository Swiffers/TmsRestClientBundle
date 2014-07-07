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
     * @param string  $path The path.
     * @param string  $slug The slug.
     * @param boolean $absolutePath Whether it is an absolute path or not.
     *
     * @return \Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem The hypermedia.
     */
    function findOne($path, $slug, $absolutePath = false);

    /**
     * Find.
     *
     * @param string  $path   The path.
     * @param array   $params The query parameters.
     * @param boolean $absolutePath Whether it is an absolute path or not.
     *
     * @return \Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection The hypermedia.
     */
    function find($path, array $params = array(), $absolutePath = false);

    /**
     * Inquire.
     *
     * @param string  $path The path.
     * @param boolean $absolutePath Whether it is an absolute path or not.
     *
     * @return \Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem The hypermedia.
     */
    function inquire($path, $absolutePath = false);

    /**
     * Crawl an URL.
     *
     * @param string  $path         The path.
     * @param array   $params       The query parameters.
     * @param boolean $absolutePath Whether it is an absolute path or not.
     *
     * @return \Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection|\Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem The hypermedia.
     */
    function crawl($path, array $params = array(), $absolutePath = false);

    /**
     * Execute an action.
     *
     * @param string  $path         The path.
     * @param string  $method       The HTTP method.
     * @param array   $params       The query parameters.
     *
     * @return mixed The return of the execution.
     */
    function execute($path, $method, array $params = array());
}
