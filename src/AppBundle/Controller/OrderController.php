<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
     /**
     * Lists all Orders entities.
     *
     * @Route("/orders", name="order_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $orders = $em->getRepository('AppBundle:Order')->getAllOrders();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $orders,
            $request->query->getInt('page', 1),
            10
        );
    
        return $this->render('orders/index.html.twig', array(
            'orders' => $pagination,
        ));
    }

    /**
     * @Route("/export-orders", name="export_orders_to_csv")
     * @Template()
     */
    public function exportToCsv()
    {
        $results = $this->getDoctrine()->getManager()
        ->getRepository('AppBundle:Order')->findAll();

        $response = new StreamedResponse();
        $response->setCallback(
            function () use ($results) {
                $handle = fopen('php://output', 'r+');
                foreach ($results as $row) {
                    //array list fields you need to export
                    $data = array(
                        $row->getId(),
                        $row->getReference(),
                        $row->getStatus(),
                        $row->getTotal(),
                        date_format($row->getDate(),'Y-m-d H:i:s'),
                    );
                    fputcsv($handle, $data);
                }
                fclose($handle);
            }
        );
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders'.time().'.csv"');

        return $response;
    }
    
}
