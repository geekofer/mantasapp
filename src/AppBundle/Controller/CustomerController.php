<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;

use AppBundle\Entity\Customer;

class CustomerController extends Controller
{
    /**
     * Lists all Customer entities.
     *
     * @Route("/customers", name="customer_index")
     * @Method("GET")
     */

    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $customers = $em->getRepository('AppBundle:Customer')->getAllCustomers();


        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $customers,
            $request->query->getInt('page', 1),
            10
        );
    
        return $this->render('customer/index.html.twig', array(
            'customers' => $pagination,
        ));
    }

     /**
     * Finds and displays a Customer entity.
     *
     * @Route("/customer/{id}", name="customer_show")
     * @Method("GET")
     */
    public function showAction(Customer $customer, Request $request)
    {
        $em     = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:Order')->getCustomerOrders($customer);

        $paidOrders   = $em->getRepository('AppBundle:Order')->getTotalByCustomer('_PAID_', $customer);
        $unPaidOrders = $em->getRepository('AppBundle:Order')->getTotalByCustomer('_PENDING_', $customer);
        
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $orders,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('customer/show.html.twig', array(
            'orders'        => $pagination,
            'paid_orders'   => $paidOrders,
            'unpaid_orders' => $unPaidOrders,
            'customer'      => $customer,
        ));
    }

    /**
     * @Route("/export-customers", name="export_customers_to_csv")
     * @Template()
     */
    public function exportToCsv()
    {
        $results = $this->getDoctrine()->getManager()
        ->getRepository('AppBundle:Customer')->findAll();

        $response = new StreamedResponse();
        $response->setCallback(
            function () use ($results) {
                $handle = fopen('php://output', 'r+');
                foreach ($results as $row) {
                    //array list fields you need to export
                    $data = array(
                        $row->getId(),
                        $row->getName(),
                        $row->getEmail(),
                        $row->getPhone(),
                        $row->getCity()
                    );
                    fputcsv($handle, $data);
                }
                fclose($handle);
            }
        );
        $response->headers->set('Content-Type', 'application/force-download');
        $response->headers->set('Content-Disposition', 'attachment; filename="customers'.time().'.csv"');

        return $response;
    }
}
