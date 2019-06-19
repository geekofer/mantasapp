<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

use AppBundle\Entity\Order;

class LoadOrderData implements FixtureInterface, OrderedFixtureInterface
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
            $this->createOrder($manager, $i);
        }
        
    }

    /**
    *
    * @param ObjectManager $manager
    * @param type $n
    */
    private function createOrder(ObjectManager $manager, $n)
    {
        $faker = Factory::create('es_ES');
        $customerRepository = $manager->getRepository('AppBundle:Customer');
        $customers = $customerRepository->findAll();
        $statuses = array('_PAID_', '_PENDING_');

        $order = new Order();
        $order->setReference($faker->swiftBicNumber);
        $order->setStatus($statuses[rand(0,count($statuses)-1)]);
        $order->setDate($this->randomDate('2018-10-01'));
        $order->setTotal(rand(1000,100000));
        $order->setCustomer($customers[array_rand($customers)]);
        
        $manager->persist($order);
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
        return 4; // the order in which fixtures will be loaded
    }
}

