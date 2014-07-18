<?php

namespace Tms\Bundle\RestClientBundle\Hypermedia;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tms\Bundle\RestClientBundle\Hypermedia\Crawling\CrawlerInterface;

/**
 * AbstractHypermedia.
 *
 * @author Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 */
abstract class AbstractHypermedia
{
    protected $crawler;

    protected $metadata;
    protected $data;
    protected $links;
    protected $actions;

    /**
     * Constructor
     *
     * @param array $raw
     */
    public function __construct(array $raw)
    {
        $this->normalize($raw);
    }

    /**
     * Set the crawler.
     *
     * @param CrawlerInterface $crawler.
     */
    public function setCrawler(CrawlerInterface $crawler)
    {
        $this->crawler = $crawler;
    }

    /**
     * Normalize array to Hypermedia object
     *
     * @param array $raw
     */
    public function normalize(array $raw)
    {
        $this->setMetadata($raw);
        $this->setData($raw);
        $this->setLinks($raw);
        $this->setActions($raw);
    }

    /**
     * Set metadata
     *
     * @param array $raw
     * @return $this;
     */
    public function setMetadata(array $raw)
    {
        if(!isset($raw['metadata'])) {
            throw new NotFoundHttpException("No 'metadata' section found in hypermedia raw.");
        }

        $this->metadata = $raw['metadata'];
    }

    /**
     * Set data
     *
     * @param array $raw
     * @return $this;
     */
    public function setData(array $raw)
    {
        if(!isset($raw['data']) && 'info' !== $raw['metadata']['type']) {
            throw new NotFoundHttpException("No 'data' section found in hypermedia raw.");
        }

        $this->data = isset($raw['data']) ? $raw['data'] : null;
    }

    /**
     * Set links
     *
     * @param array $raw
     * @return $this;
     */
    public function setLinks(array $raw)
    {
        if(!isset($raw['links']) && 'info' !== $raw['metadata']['type']) {
            throw new NotFoundHttpException("No 'links' section found in hypermedia raw.");
        }

        $this->links = isset($raw['links']) ? $raw['links'] : null;

        return $this;
    }

    /**
     * Set actions
     *
     * @param array $raw
     */
    public function setActions(array $raw)
    {
        if(!isset($raw['actions'])) {
            throw new NotFoundHttpException("No 'actions' section found in hypermedia raw.");
        }

        $this->actions = $raw['actions'];
    }

    /**
     * Get a specific metadata
     *
     * @param string $name
     * @return mixed $metadata
     */
    public function getMetadata($name = null)
    {
        if(is_null($name)) {
            return $this->getAllMetadata();
        }

        if($this->hasMetadata($name)) {
            return $this->metadata[$name];
        }

        throw new NotFoundHttpException(sprintf("No '%s' metadata found.", $name));
    }

    /**
     * Check if a specific metadata exists
     *
     * @param string $name
     * @return boolean
     */
    public function hasMetadata($name)
    {
        return isset($this->metadata[$name]);
    }

    /**
     * Get all metadata
     *
     * @return array
     */
    public function getAllMetadata()
    {
        return $this->metadata;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get links
     *
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Get a specific link
     *
     * @param string $name
     * @return array
     */
    public function getLink($name)
    {
        if($this->hasLink($name)) {
            return $this->links[$name];
        }

        throw new NotFoundHttpException(sprintf("No '%s' link found.", $name));
    }

    /**
     * Check if a specific link exists
     *
     * @param string $name
     * @return boolean
     */
    public function hasLink($name)
    {
        return isset($this->links[$name]);
    }

    /**
     * Get a link URL
     *
     * @param string $name
     * @return string URL
     */
    public function getLinkUrl($name)
    {
        $link = $this->getLink($name);

        return $link['href'];
    }

    /**
     * Get a link query array
     *
     * @param string $name
     * @return array
     */
    public function getLinkUrlQueryArray($name)
    {
        $queryString = parse_url($this->getLinkUrl($name), PHP_URL_QUERY);
        parse_str($queryString, $queryArray);

        return $queryArray;
    }

    /**
     * Get a link path
     *
     * @param string $name
     * @return string
     */
    public function getLinkUrlPath($name)
    {
        return parse_url($this->getLinkUrl($name), PHP_URL_PATH);
    }

    /**
     * Get actions
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Follow a link URL to retrieve new hypermedia object
     *
     * @param string  $absoluteUrl
     * @param array  $params
     *
     * @return mixed HypermediaCollection OR HypermediaItem
     */
    protected function followUrl($absoluteUrl, array $params = array())
    {
        return $this->crawler->crawl($absoluteUrl, $params, true);
    }

    /**
     * Follow a link name to retrieve new hypermedia object
     *
     * @param string $name
     * @return HypermediaItem or HypermediaCollection
     */
    public function followLink($name)
    {
        return $this->followUrl($this->getLinkUrl($name));
    }

    /**
     * Execute an action of the hypermedia
     *
     * @param string $name   The name of the action
     * @param array  $params The parameters of the query
     * @param string $method The HTTP method
     *
     * @return mixed The result of the action
     */
    public function executeAction($name, array $params = array(), $method = '')
    {
        $action = null;
        $possibleActions = array();

        foreach ($this->actions as $actionName => $actionMethods) {
            $possibleActions[] = $actionName;

            if ($name === $actionName) {
                if (2 <= count($actionMethods)) {
                    if (empty($method)) {
                        $possibleMethods = array();

                        foreach ($actionMethods as $actionMethod) {
                            $possibleMethods[] = $actionMethod['method'];
                        }

                        throw new \LogicException(sprintf(
                            'The method must be defined because there is many possible methods ["%s"] for the action "%s".',
                            implode($possibleMethods, '", "'),
                            $name
                        ));
                    }

                    foreach ($actionMethods as $actionMethod) {
                        if ($actionMethod['method'] === $method) {
                            $action = $actionMethod;

                            break 2;
                        }
                    }

                    throw new \LogicException(sprintf(
                        'There is no method "%s" for the action "%s".',
                        $method,
                        $name
                    ));
                }

                $action = $actionMethods[0];
            }
        }

        if (null === $action) {
            throw new \LogicException(sprintf(
                'There is no action "%s" in the list of available actions ["%s"].',
                $name,
                implode($possibleActions, '", "')
            ));
        }

        foreach ($action['requiredParams'] as $paramName => $paramRequirements) {
            if (!isset($params[$paramName])) {
                if (isset($this->data[$paramName])) {
                    $params[$paramName] = $this->data[$paramName];
                }
            }

            if (!preg_match(sprintf('/^%s$/', $paramRequirements), $params[$paramName])) {
                throw new \LogicException(sprintf(
                    'The value "%s" of the parameter "%s" of the action "%s" must respect the requirements "%s".',
                    $params[$paramName],
                    $paramName,
                    $name,
                    $paramRequirements
                ));
            }

            if (in_array(strtoupper($action['method']), array('PUT', 'PATCH'))) {
                $this->setDataField($paramName, $params[$paramName]);
            }

            if (!isset($this->data[$paramName])) {
                throw new \LogicException(sprintf(
                    'The parameter "%s" is required for the action "%s".',
                    $paramName,
                    $name
                ));
            }
        }

        foreach ($action['optionalParams'] as $paramName => $paramRequirements) {
            if (!array_key_exists($paramName, $params)) {
                if (array_key_exists($paramName, $this->data)) {
                    $params[$paramName] = $this->data[$paramName];
                }
            }

            if (array_key_exists($paramName, $params)) {
                if (!preg_match(sprintf('/^%s$/', $paramRequirements), $params[$paramName])) {
                    throw new \LogicException(sprintf(
                        'The value "%s" of the parameter "%s" of the action "%s" must respect the requirements "%s".',
                        $params[$paramName],
                        $paramName,
                        $name,
                        $paramRequirements
                    ));
                }

                if (in_array(strtoupper($action['method']), array('PUT', 'PATCH'))) {
                    $this->setDataField($paramName, $params[$paramName]);
                }
            }
        }

        return $this->crawler->execute($action['href'], $action['method'], $params, true);
    }
}
