<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia\Crawling;

use Da\ApiClientBundle\Http\Rest\RestApiClientInterface;
use Da\ApiClientBundle\Http\Response;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaItem;
use Tms\Bundle\RestClientBundle\Hypermedia\HypermediaCollection;
use Tms\Bundle\RestClientBundle\Factory\HypermediaFactory;
use Tms\Bundle\RestClientBundle\Hypermedia\hydratation\HypermediaHydratationHandlerInterface;

/**
 * CrawlingPath represents a path that a crawler can follow.
 *
 * @author Thomas Prelot <thomas.prelot@tessi.fr>
 */
class CrawlingPath implements CrawlingPathInterface
{
    /**
     * The hydratation handler.
     *
     * @var HypermediaHydratationHandlerInterface
     */
    protected $hydratationHandler;

    /**
     * The crawler.
     *
     * @var CrawlerInterface
     */
    protected $crawler;

    /**
     * The api client.
     *
     * @var RestApiClientInterface
     */
    protected $apiClient;

    /**
     * Constructor
     *
     * @param HypermediaHydratationHandlerInterface $hydratationHandler The hydratation handler.
     * @param CrawlerInterface                      $crawler            The crawler.
     * @param RestApiClientInterface                $apiClient          An api client.
     */
    public function __construct(
        HypermediaHydratationHandlerInterface $hydratationHandler,
        CrawlerInterface $crawler,
        RestApiClientInterface $apiClient
    )
    {
        $hydratationHandler->setCrawler($crawler);

        $this->hydratationHandler = $hydratationHandler;
        $this->crawler = $crawler;
        $this->apiClient = $apiClient;
    }

    /**
     * {@inheritdoc}
     */
    public function matchPath($path)
    {
        $endpointRoot = $this->apiClient->getEndpointRoot();

        return $endpointRoot === substr($path, 0, strlen($endpointRoot));
    }

    /**
     * {@inheritdoc}
     */
    public function findOne($path, $param, $absolutePath = false)
    {
        $path = sprintf("%s/%s", $param, $slug);

        $hypermedia = $this->crawl($path, array(), $absolutePath);

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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getPathInfo($path, $absolutePath = false)
    {
        $path = sprintf("%s/info", $path);

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
     * {@inheritdoc}
     */
    public function crawl($path, array $params = array(), $absolutePath = false)
    {
        if (!$absolutePath && $path[0] !== '/') {
            $path = sprintf('/%s', $path);
        }

        $hypermedia = $this->hydratationHandler->hydrate(
            $this
                ->apiClient
                ->get($path, $params, array(), false, $absolutePath)
                ->getContent(true)
        );

        return $hypermedia;
    }

    /**
     * {@inheritdoc}
     */
    public function execute($path, $method, array $params = array())
    {
        $class = new \ReflectionClass($this->apiClient);

        $method = strtolower($method);

        if (!$class->hasMethod($method)) {
            throw new \LogicException(sprintf(
                'The HTTP method "%s" does not exist or has not been implemented.',
                $method
            ));
        }

        // Handle absolute URL path.
        if ($match = $this->matchPath($path)) {
            $endpointRoot = $this->apiClient->getEndpointRoot();

            $path = substr($path, strlen($endpointRoot));
        } else if ($path[0] !== '/') {
            $path = sprintf('/%s', $path);
        }

        $result = $this
            ->apiClient
            ->$method($path, $params)
            ->getContent(true)
        ;

        if (is_array($result) && $result['metadata']) {
            $result = $this->hydratationHandler->hydrate($result);
        }

        return $result;
    }
}
