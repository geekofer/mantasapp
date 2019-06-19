<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

use AppBundle\Entity\Customer;

class LoadCustomerData implements FixtureInterface, OrderedFixtureInterface
{

    /** @var Generator */
    protected $faker;

    /**
    *
    * @param ObjectManager $manager
    */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++) {
            $this->createCustomer($manager, $i);
        }
        
    }

    /**
    *
    * @param ObjectManager $manager
    * @param type $n
    */
    private function createCustomer(ObjectManager $manager, $n)
    {
        $faker = Factory::create('es_ES');
        $city = array('Medellín', 'Calí', 'Pereira', 'Bogotá', 'Barranquilla', 'Santa Marta');
        
        $customer = new Customer();
        $customer->setName($faker->name);
        $customer->setEmail($faker->email);
        $customer->setPhone($faker->phoneNumber);
        $customer->setCity($city[rand(0,count($city)-1)]);
        $customer->setRegisterDate($this->randomDate('2018-10-01'));

        $manager->persist($customer);
        $manager->flush();
    }
    
    /**
     * @return int
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
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
}