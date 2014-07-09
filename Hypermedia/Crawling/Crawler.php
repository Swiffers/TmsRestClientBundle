<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Crawling;

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
class Crawler implements CrawlerInterface
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
     * @param string                $id           The id of the crawling path.
     * @param CrawlingPathInterface $crawlingPath The crawling path.
     */
    public function setCrawlingPath($id, CrawlingPathInterface $crawlingPath)
    {
        $this->crawlingPaths[$id] = $crawlingPath;
    }

    /**
     * Get the crawling path ids.
     *
     * @return array The crawling path ids.
     */
    public function getCrawlingPathIds()
    {
        return array_keys($this->crawlingPaths);
    }

    /**
     * {@inheritdoc}
     */
    public function go($crawlingPathId)
    {
        if (!isset($this->crawlingPaths[$crawlingPathId])) {
            throw new \LogicException(sprintf(
                'The crawling path "%s" is not defined.',
                $crawlingPathId
            ));
        }

        return $this->crawlingPaths[$crawlingPathId];
    }

    /**
     * {@inheritdoc}
     */
    public function crawl($url, array $params = array())
    {
        $crawlingPath = $this->retrieveCrawlingPath($url);
        $params = $this->retrieveUrlParams($url);
        $url = $this->retrieveBaseUrl($url);

        return $crawlingPath->crawl($url, $params, true);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($url, $method, array $params = array())
    {
        $crawlingPath = $this->retrieveCrawlingPath($url);
        $params = $this->retrieveUrlParams($url);
        $url = $this->retrieveBaseUrl($url);

        return $crawlingPath->execute($url, $method, $params);
    }

    /**
     * Retrieve a crawling path from an URL.
     *
     * @param string $url The URL.
     *
     * @return CrawlingPathInterface The crawling path.
     */
    protected function retrieveCrawlingPath($url)
    {
        foreach ($this->crawlingPaths as $crawlingPath) {
            if ($crawlingPath->matchPath($url)) {
                return $crawlingPath;
            }
        }

        throw new \LogicException(sprintf(
            'There is no crawling path for the url "%s".',
            $url
        ));
    }

    /**
     * Retrieve the params of the query string of an URL.
     *
     * @param string $url The URL.
     *
     * @return array The params.
     */
    protected function retrieveUrlParams($url)
    {
        $params = array();
        $parsedUrl = parse_url($url);

        if (isset($parsedUrl['query'])) {
            $parameters = explode('&', $parsedUrl['query']);

            foreach ($parameters as $parameter) {
                list($name, $value) = explode('=', $parameter);
                $params[$name] = $value;
            }
        }

        return $params;
    }

    /**
     * Retrieve the base URL of an URL.
     *
     * @param string $url The URL.
     *
     * @return string The base URL.
     */
    protected function retrieveBaseUrl($url)
    {
        $parsedUrl = parse_url($url);

        return sprintf('%s://%s%s',
            $parsedUrl['scheme'],
            $parsedUrl['host'],
            $parsedUrl['path']
        );
    }
}
