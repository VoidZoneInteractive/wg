<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 11.10.15
 * Time: 16:14
 */

namespace FeedBundle\Controller;

use FeedBundle\Input\VouchersAPI;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class Api extends Controller{

    /**
     * Get voucher data from files, and print them out
     *
     * If its a first call to api we print first voucher data, on any subsequent
     * request we print out the other one
     * @Route("/api/vouchers")
     * @Method({"GET"})
     */
    public function vouchersAction()
    {
        $vouchersApi = new VouchersAPI();
        $responseContent = $vouchersApi->getVouchers($this);

        $response = new Response($responseContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Clear APC cache
     *
     * @Route("/api/clear_cache")
     * @Method({"GET"})
     * @return Response
     */
    public function clearCacheAction()
    {
        apc_clear_cache();
        apc_clear_cache('user');

        apc_store('was_request_called', false);

        $response = new Response(json_encode(array('status' => 'OK')));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
} 