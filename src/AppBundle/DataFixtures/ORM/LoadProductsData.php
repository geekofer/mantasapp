<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

use AppBundle\Entity\Product;

class LoadProductData implements FixtureInterface, OrderedFixtureInterface
{

    /** @var Generator */
    protected $faker;

    /**
    *
    * @param ObjectManager $manager
    */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 4; $i++) {
            $this->createProduct($manager, $i);
        }
        
    }

    /**
    *
    * @param ObjectManager $manager
    * @param type $n
    */
    private function createProduct(ObjectManager $manager, $n)
    {
        $faker = Factory::create('es_ES');
        $products = array('Tomate', 'Manzana', 'Pera', 'Limón');

        $product = new Product();
        $product->setName($products[$n]);
        $product->setDescription($faker->text);
        $product->setPrice(rand(1000,100000));

        $manager->persist($product);
        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}