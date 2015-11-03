<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 12.10.15
 * Time: 17:35
 */

namespace WebgearsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use WebgearsBundle\External\Fetcher;
use WebgearsBundle\Store\Store;


class ExternalApi extends Controller {
    /**
     * Action to pull data from external service
     * @Route("/external_api/pull_data")
     * @Method({"GET"})
     */
    public function pullDataAction()
    {

        $request = Request::createFromGlobals();

        if ($request->isXmlHttpRequest())
        {
            $fetcher = $this->get('webgears_fetcher');
            $data = $fetcher->construct()->getResult();

            $store = $this->get('webgears_store');
            $store->prepareShops($data->shops);
            $store->insertShops();

            $store->prepareVouchers($data->vouchers);
            $store->insertAndUpdateVouchers();

            $responseBody = array(
                'status' => 'OK',
            );

            $response = new Response(json_encode($responseBody));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

        $responseBody = array(
            'status' => 'ERROR',
        );

        $response = new Response(json_encode($responseBody));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}