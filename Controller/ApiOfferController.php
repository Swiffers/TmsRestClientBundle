<?php

/**
 *
 * @author:  Gabriel BONDAZ <gabriel.bondaz@idci-consulting.fr>
 * @author:  Pierre FERROLLIET <pierre.ferrolliet@idci-consulting.fr>
 * @author:  Jean-Philippe CHATEAU <jp.chateau@trepia.fr>
 * @license: GPL
 *
 */

namespace Tms\Bundle\RestClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Offer API REST controller
 */
class ApiOfferController extends Controller
{
    /**
     * Retrieve a set of offers
     *
     * @param string $name
     * @param string $reference
     * @param string $status
     * @param integer $limit
     * @param integer $offset
     * @param integer $page
     * @param string $sort_field
     * @param string $sort_order
     */
    public function getOffersAction(
        $name       = null,
        $reference  = null,
        $status     = null,
        $limit      = null,
        $offset     = null,
        $page       = null,
        $sort_field = null,
        $sort_order = null
    )
    {
        try {
            $hypermediaCollection = $this
                ->get('tms_rest_client.crawlers.operation_manager.offers')
                ->findAll($this->getRequest()->query->all())
            ;
            $hypermediaCollection->getAllMetaData();
            var_dump($hypermediaCollection); die;
        } catch (\Da\ApiClientBundle\Exception\ApiHttpResponseException $e) {
            switch ($e->getHttpCode()) {
                case '404':
                    throw new HttpNotFoundException();
                case '500':
                    throw new AccessDeniedHttpException();
            }
        }
    }

    /**
     * Retrieve an offer
     *
     * @param string $id
     */
    public function getOfferAction($id)
    {
        try {
            var_dump($this->getRequest()->query); die;
            $hypermediaItem = $this
                ->get('tms_rest_client.crawlers.operation_manager.offers')
                ->findOneBy($this->getRequest()->query->all())
            ;
            var_dump($hypermediaItem); die;
        } catch (\Da\ApiClientBundle\Exception\ApiHttpResponseException $e) {
            switch ($e->getHttpCode()) {
                case '404':
                    throw new HttpNotFoundException();
                case '500':
                    throw new AccessDeniedHttpException();
            }
        }
    }
}
