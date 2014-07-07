<?php

namespace Tms\Bundle\RestClientBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     * @Template("TmsRestClientBundle:Browser:crawl.json.twig")
     */
    public function findOneAction($crawling_path)
    {
        $this->container->get('tms_rest_client.hypermedia.crawler')
            ->go($crawling_path)
            ->findOne($path)
        ;

        return array('crawlingPath' => $crawling_path);
    }

    /**
     * Find.
     *
     * @Route("/find/{crawling_path}")
     * @Template("TmsRestClientBundle:Browser:crawl.json.twig")
     */
    public function findAction($crawling_path)
    {
        $this->container->get('tms_rest_client.hypermedia.crawler')
            ->go($crawling_path)
            ->find($path)
        ;

        return array('crawlingPath' => $crawling_path);
    }

    /**
     * Inquire.
     *
     * @Route("/inquire/{crawling_path}")
     * @Template("TmsRestClientBundle:Browser:crawl.json.twig")
     */
    public function inquireAction($crawling_path)
    {
        $this->container->get('tms_rest_client.hypermedia.crawler')
            ->go($crawling_path)
            ->inquire($path)
        ;

        return array('crawlingPath' => $crawling_path);
    }

    /**
     * Crawl.
     *
     * @Route("/crawl")
     * @Template("TmsRestClientBundle:Browser:crawl.json.twig")
     */
    public function crawlAction()
    {
        $this->container->get('tms_rest_client.hypermedia.crawler')
            ->crawl($url)
        ;

        return array();
    }
}