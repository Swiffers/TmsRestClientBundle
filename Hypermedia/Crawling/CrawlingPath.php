<?php

namespace Tms\Bundle\RestClientBundle\Crawler;

use Da\ApiClientBundle\Http\Rest\RestApiClientInterface;
use Da\ApiClientBundle\Http\Response;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;
use Tms\Bundle\RestClientBundle\Factory\HypermediaFactory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * CrawlingPath represents a path that a crawler can follow.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class CrawlingPath
{
    /**
     * The api client.
     *
     * @var RestApiClientInterface
     */
    protected $apiClient;

    /**
     * Constructor
     *
     * @param RestApiClientInterface $apiClient An api client.
     */
    public function __construct(RestApiClientInterface $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    /**
     * Whether or not a path is matching with this crawling path.
     *
     * @param string $path The path to match.
     *
     * @return boolean True if the path match, false otherwise.
     */
    public function matchPath($path)
    {
        $endpointRoot = $this->apiClient->getEndpointRoot();

        return $endpointRoot === substr($path, 0, strlen($endpointRoot));
    }

    /**
     * Find one.
     *
     * @param string  $path The path.
     * @param string  $slug The slug.
     * @param boolean $absolutePath Whether it is an absolute path or not.
     *
     * @return HypermediaItem The hypermedia.
     */
    public function findOne($path, $slug, $absolutePath = false)
    {
        $path = sprintf("%s/{slug}", $path, $slug);

        $hypermedia = $this->crawl($path, array('slug' => $slug), $absolutePath);

        if ($hypermedia instanceof HypermediaItem) {
            return $hypermedia;
        }

        $class = get_class($hypermedia);

        throw new \LogicException(sprintf(
            'The method "findOne" returns a "\Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem", a "%s" given instead.',
            $class ? $class : gettype($hypermedia)
        ));
    }

    /**
     * Find.
     *
     * @param string  $path   The path.
     * @param array   $params The query parameters.
     * @param boolean $absolutePath Whether it is an absolute path or not.
     *
     * @return HypermediaCollection The hypermedia.
     */
    public function find($path, array $params = array(), $absolutePath = false)
    {
        $hypermedia = $this->crawl($path, $params, $absolutePath);

        if ($hypermedia instanceof HypermediaCollection) {
            return $hypermedia;
        }

        $class = get_class($hypermedia);

        throw new \LogicException(sprintf(
            'The method "find" returns a "\Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection", a "%s" given instead.',
            $class ? $class : gettype($hypermedia)
        ));
    }

    /**
     * Inquire.
     *
     * @param string  $path The path.
     * @param boolean $absolutePath Whether it is an absolute path or not.
     *
     * @return HypermediaItem The hypermedia.
     */
    public function inquire($path, $absolutePath = false)
    {
        $path = sprintf("%s/inquiry", $path);

        $hypermedia = $this->crawl($path, array(), $absolutePath);

        if ($hypermedia instanceof HypermediaItem) {
            return $hypermedia;
        }

        $class = get_class($hypermedia);

        throw new \LogicException(sprintf(
            'The method "inquire" returns a "\Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem", a "%s" given instead.',
            $class ? $class : gettype($hypermedia)
        ));
    }

    /**
     * Magic call.
     */
    public function __call($method, $arguments)
    {
        if ($this->matchMethod($method, 'findOne')) {
            $path = $this->retrievePathFromMethodName($method, 'findOne');
            $slug = $arguments[0];
            $absolutePath = isset($arguments[1]) ? $arguments[1] : false;

            return $this->findOne($path, $slug, $absolutePath);
        } else if ($this->matchMethod($method, 'find')) {
            $path = $this->retrievePathFromMethodName($method, 'find');
            $args = isset($arguments[0]) ? $arguments[0] : array();
            $absolutePath = isset($arguments[1]) ? $arguments[1] : false;

            return $this->find($path, $args, $absolutePath);
        } else if ($this->matchMethod($method, 'inquire')) {
            $path = $this->retrievePathFromMethodName($method, 'inquire');
            $absolutePath = isset($arguments[0]) ? $arguments[0] : false;

            return $this->inquire($path, $absolutePath);
        }

        throw new \LogicException(sprintf(
            'The method "%s" is not defined.',
            $method
        ));
    }

    /**
     * Whether or not a method is matching a pattern.
     *
     * @param string $method  The name of the method.
     * @param string $pattern The pattern to match.
     *
     * @return boolean True if the method match, false otherwise.
     */
    private function matchMethod($method, $pattern)
    {
        return substr($method, 0, strlen($pattern)) === $pattern;
    }

    /**
     * Retrieve a path from a method name.
     *
     * @param string $method  The name of the method.
     * @param string $pattern The pattern of the method.
     *
     * @return string The path.
     */
    private function retrievePathFromMethodName($method, $pattern)
    {
        $method = substr($method, 0, strlen($pattern));

        return strtolower(preg_replace('/([A-Z])/', '_$n', $method));
    }

    /**
     * Crawl an URL (absolute or relative)
     *
     * @param string  $path         The path.
     * @param array   $params       The query parameters.
     * @param boolean $absolutePath Whether it is an absolute path or not.
     *
     * @return HypermediaCollection | HypermediaItem
     */
    protected function crawl($path, array $params = array(), $absolutePath = false)
    {
        $hypermedia = HypermediaFactory::build(
            $this
                ->apiClient
                ->get($path, $params, array(), false, $absolutePath)
                ->getContent(true)
        );

        $hypermedia->setCrawler($this);

        return $hypermedia;
    }
}
