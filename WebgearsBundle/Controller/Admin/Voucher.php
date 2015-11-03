<?php
/**
 * Created by PhpStorm.
 * User: grzegorzgurzeda
 * Date: 14.10.15
 * Time: 21:38
 */

namespace WebgearsBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class Voucher extends Controller {

    /**
     * List vouchers
     * @Route("/admin/voucher")
     * @Method({"GET", "POST"})
     */
    public function listAction()
    {
        $request = Request::createFromGlobals();

        // Handle various XHR request
        if ($request->isXmlHttpRequest())
        {
            switch ($request->request->get('action', null))
            {
                case 'update-data':
                    $vouchers = $this->getDoctrine()->getRepository('WebgearsBundle:Voucher')->findBy(array('checked' => 0), array('found_date' => 'DESC'));
                    $content = $this->renderView('webgears/admin/voucher/list_ajax.html.twig', array(
                        'vouchers' => $vouchers,
                    ));
                    $status = 'OK';
                    break;

                case 'check-voucher':
                    try {
                        $this->getDoctrine()->getEntityManager()->getRepository("WebgearsBundle:Voucher")->updateChecked($request->request->get('voucher-id'));
                    } catch (Exception $e)
                    {
                        $status = 'ERROR';
                        $content = $e->getMessage();

                        $responseBody = array(
                            'status' => $status,
                            'content' => $content,
                        );

                        $response = new Response(json_encode($responseBody));
                        $response->headers->set('Content-Type', 'application/json');

                        return $response;
                    }

                    $content = $request->request->get('voucher-id');
                    $status = 'OK';
                    break;

                default:
                    $status = 'ERROR';
                    $content = 'Invalid request.';
                    break;
            }

            $responseBody = array(
                'status' => $status,
                'content' => $content,
            );

            $response = new Response(json_encode($responseBody));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }
        else
        {
            return $this->render('webgears/admin/voucher/list.html.twig', array(
                'title' => 'Vouchers list',
                'fetchApi' => '/external_api/pull_data',
            ));
        }

    }
} 