<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

use AppBundle\Entity\OrderDetail;

class LoadOrderDetailData implements FixtureInterface, OrderedFixtureInterface
{

    /** @var Generator */
    protected $faker;

    /**
    *
    * @param ObjectManager $manager
    */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 500; $i++) {
            $this->createOrderDetail($manager, $i);
        }
        
    }

    /**
    *
    * @param ObjectManager $manager
    * @param type $n
    */
    private function createOrderDetail(ObjectManager $manager, $n)
    {
        $faker = Factory::create('es_ES');
        $productRepository = $manager->getRepository('AppBundle:Product');
        $orderRepository   = $manager->getRepository('AppBundle:Order');

        $products = $productRepository->findAll();
        $orders   = $orderRepository->findAll();
        

        $orderDetail = new OrderDetail();
        $orderDetail->setQuantity(rand(1,10));
        $orderDetail->setDate($this->randomDate('2018-10-01'));
        $orderDetail->setPrice(rand(1000,100000));
        $orderDetail->setProduct($products[array_rand($products)]);
        $orderDetail->setOrder($orders[array_rand($orders)]);
        
        $manager->persist($orderDetail);
        $manager->flush();
    }

    /**
     * Method to generate random date between and start date and now
     * @param string $sStartDate
     * @return string
     */
    function randomDate($sStartDate)
    {
        $start    = new \Datetime($sStartDate);
        $end      = new \Datetime();
        return new \DateTime('@' . mt_rand($start->getTimestamp(), $end->getTimestamp()));
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }
}

