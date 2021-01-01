<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Property;
use App\Entity\PropertyLike;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use \Faker\Factory;

class AppFixtures extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoderInterface)
    {
        $this->passwordEncoder = $userPasswordEncoderInterface;
    }


    public function load(ObjectManager $manager)
    {
        $users = [];
        $properties = [];

        $faker = Factory::create();
        
        $user = (new User())->setEmail('linda.ouerfelli@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, '123456'));

        $manager->persist($user);

        for ($i=0; $i < 20; $i++) { 
            $user = (new User())->setEmail($faker->email);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $faker->password(4)));

            $manager->persist($user);

            $users[] = $user;
        }
        

        for ($i=0; $i < 20; $i++) { 
            $property = (new Property())
                    ->setAddress($faker->address)
                    ->setBedrooms($faker->randomDigit)
                    ->setCity($faker->city)
                    ->setDescription($faker->text())
                    ->setFloor($faker->randomDigit)
                    ->setHeat($faker->randomElement(array(1, 0)))
                    ->setPostalCode($faker->postcode)
                    ->setPrice($faker->randomFloat(3, 50000, 1000000))
                    ->setRooms($faker->randomDigit)
                    ->setSurface($faker->randomFloat(3, 40, 1000))
                    ->setTitle('Property - '. ($i+1))
            ;
            $manager->persist($property);
            $properties[] = $property;
        } 
        
        for ($i=0; $i < 10; $i++) { 
            $like = (new PropertyLike())
                ->setProperty($faker->randomElement($properties))
                ->setUser($faker->randomElement($users));
            $manager->persist($like);
        }
        
        $manager->flush();
    }
}
