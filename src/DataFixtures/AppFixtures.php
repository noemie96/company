<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        //gestion de l'admin
        $adminUser = new User();
        $adminUser->setFirstName('Noémie')
            ->setLastName('Françouille')
            ->setEmail('nono@epse.be')
            ->setPassword($this->passwordEncoder->encodePassword($adminUser,'password'))
            ->setRoles(['ROLE_ADMIN']);
        $manager->persist($adminUser);

        //gestion des users
        for($u=0; $u<10; $u++)
        {
            $user = new User();
            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setEmail($faker->email())
                ->setPassword($this->passwordEncoder->encodePassword($user,'password'));
            $manager->persist($user);
        }

        //category
        for($c=0; $c<10; $c++)
        {
            $category = new Category();
            $category->setTitle($faker->lexify('category ????'));
            $manager->persist($category);

            //products
            for($p=0; $p< rand(5,20); $p++)
            {
                $product = new Product();
                $product->setTitle($faker->lexify('Title ??????'))
                    ->setPrice($faker->randomFloat(2, 50, 2000))
                    ->setDescription($faker->text())
                    ->setCategory($category);
                $manager->persist($product);
            }
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}