<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     */
    public function indexAction()
    {
      $em = $this->getDoctrine()->getManager();
      
      $customers     = $em->getRepository('AppBundle:Customer')->findAll();
      $lastCustomers = $em->getRepository('AppBundle:Customer')->getByLastMonth();

      $orders        = $em->getRepository('AppBundle:Order')->findAll();
      $paidOrders    = $em->getRepository('AppBundle:Order')->getTotalByStatus('_PAID_');
      $pendingOrders = $em->getRepository('AppBundle:Order')->getTotalByStatus('_PENDING_');

      $mostPopularProduct = $em->getRepository('AppBundle:OrderDetail')->getMostPopularProduct();

      return $this->render('AppBundle:Default:index.html.twig',array(
        'total_customers' => count($customers),
        'total_last_customers' => count($lastCustomers),
        'total_orders' => count($orders),
        'amount_paid_orders' => $paidOrders[0],
        'amount_pending_orders' => $pendingOrders,
        'most_popular' => $mostPopularProduct[0]
      ));
    }
}
