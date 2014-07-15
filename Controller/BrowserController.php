<?php

namespace Tms\Bundle\RestClientBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tms\Bundle\RestClientBundle\Hypermedia\Crawling\AbstractHypermedia;

/**
 * @author Thomas Prelot <thomas.prelot@gmail.com>
 *
 * @Route("/rest-client/browser")
 */
class BrowserController extends ContainerAware
{
    /**
     * Index.
     *
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $crawler = $this->container->get('tms_rest_client.hypermedia.crawler');

        return array('crawlingPaths' => $crawler->getCrawlingPathIds());
    }

    /**
     * Go.
     *
     * @Route("/go/{crawling_path}")
     * @Template()
     */
    public function goAction($crawling_path)
    {
        // Check the existence of the crawling path.
        $this->container->get('tms_rest_client.hypermedia.crawler')
            ->go($crawling_path)
        ;

        return array('crawlingPath' => $crawling_path);
    }

    /**
     * Find one.
     *
     * @Route("/find-one/{crawling_path}")
     * @Template("TmsRestClientBundle:Browser:crawl.html.twig")
     */
    public function findOneAction($crawling_path)
    {
        $request = $this->container->get('request');
        $queryParameters = $request->query;

        $path = $queryParameters->get('path');
        $slug = $queryParameters->get('slug');

        $hypermedia = $this->container->get('tms_rest_client.hypermedia.crawler')
            ->go($crawling_path)
            ->findOne($path, $slug)
        ;

        $source = $this->container->get('router')->generate(
            'tms_restclient_browser_findone',
            array_merge($request->query->all(), $request->attributes->all())
        );

        return array(
            'crawlingPath' => $crawling_path,
            'hypermedia' => $hypermedia,
            'source' => $source
        );
    }

    /**
     * Find.
     *
     * @Route("/find/{crawling_path}")
     * @Template("TmsRestClientBundle:Browser:crawl.html.twig")
     */
    public function findAction($crawling_path)
    {
        $request = $this->container->get('request');
        $queryParameters = $request->query;

        $path = $queryParameters->get('path');
        $params = $queryParameters->get('params', '');
        if (!$params) {
            $params = array();
        } else {
            $params = json_decode($params, true);
        }

        if (null === $params) {
            $content = $this->container->get('templating')->render(
                'TmsRestClientBundle:Browser:error.html.twig',
                array(
                    'error' => 'Bad JSON for find parameters.'
                )
            );

            return new Response($content);
        }

        $hypermedia = $this->container->get('tms_rest_client.hypermedia.crawler')
            ->go($crawling_path)
            ->find($path, $params)
        ;

        $source = $this->container->get('router')->generate(
            'tms_restclient_browser_find',
            array_merge($request->query->all(), $request->attributes->all())
        );

        return array(
            'crawlingPath' => $crawling_path,
            'hypermedia' => $hypermedia,
            'source' => $source
        );
    }

    /**
     * Get the info of the path.
     *
     * @Route("/info/{crawling_path}")
     * @Template("TmsRestClientBundle:Browser:crawl.html.twig")
     */
    public function getPathInfoAction($crawling_path)
    {
        $request = $this->container->get('request');
        $path = $request->query->get('path');

        $hypermedia = $this->container->get('tms_rest_client.hypermedia.crawler')
            ->go($crawling_path)
            ->getPathInfo($path)
        ;

        $source = $this->container->get('router')->generate(
            'tms_restclient_browser_getpathinfo',
            array_merge($request->query->all(), $request->attributes->all())
        );

        return array(
            'crawlingPath' => $crawling_path,
            'hypermedia' => $hypermedia,
            'source' => $source
        );
    }

    /**
     * Crawl.
     *
     * @Route("/crawl")
     * @Template("TmsRestClientBundle:Browser:crawl.html.twig")
     */
    public function crawlAction()
    {
        $request = $this->container->get('request');
        $url = $request->query->get('url');
        $params = $request->query->get('params', array());
        if (!$params) {
            $params = array();
        }

        $hypermedia = $this->container->get('tms_rest_client.hypermedia.crawler')
            ->crawl($url, $params)
        ;

        $source = $this->container->get('router')->generate(
            'tms_restclient_browser_crawl',
            $request->query->all()
        );

        return array(
            'hypermedia' => $hypermedia,
            'source' => $source
        );
    }

    /**
     * Execute.
     *
     * @Route("/execute")
     * @Template("TmsRestClientBundle:Browser:crawl.html.twig")
     */
    public function executeAction()
    {
        $request = $this->container->get('request');
        $url = $request->query->get('url');
        $method = $request->query->get('method');
        $params = $request->request->get('params', array());
        if (!$params) {
            $params = array();
        }

        $result = $this->container->get('tms_rest_client.hypermedia.crawler')
            ->execute($url, $method, $params)
        ;

        if ($result instanceof AbstractHypermedia) {
            return array('hypermedia' => $hypermedia);
        }

        $source = $request->query->get('source');

        return new RedirectResponse($source);
    }
}