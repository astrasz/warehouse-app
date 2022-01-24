<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->ProductData() as [$name, $price, $stock]) {
            $product = new Product();
            $product->setCreatedBy($this->getReference('user'. rand(1,4)));
            $product->setName($name);
            $product->setPrice($price);
            $product->setStock($stock);
            
            $manager->persist($product);
            
        }

        // $product->setCreatedBy($this->getReference('user'));
        // $this->addReference('product', $product);

        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }


    private function ProductData()
    {
        return [
            ['bed', 12.34, 200],
            ['table', 10.50, 120],
            ['desk', 87, 2],
            ['door', 23.98, 73],
            ['wardrobe', 183, 1],
            ['lamp', 2.4, 87],
            ['shelf', 4.8, 11],
            ['windowsill', 3, 11],
            ['window', 124, 4],
            ['dresser', 150, 1],
            ['bed2', 212.34, 2200],
            ['table2', 210.50, 2120],
            ['desk2', 287, 22],
            ['door2', 223.98, 273],
            ['wardrobe2', 2183, 21],
            ['lamp2', 22.4, 287],
            ['shelf2', 24.8, 211],
            ['windowsill2', 23, 211],
            ['window2', 2124, 24],
            ['dresser2', 2150, 21],
        ];
    }
}
