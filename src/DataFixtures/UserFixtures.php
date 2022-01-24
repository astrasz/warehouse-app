<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $num=1;
        foreach ($this->UserData() as [$email, $roles, $plainPassword]) {
            $user = new User();
            $user->setEmail($email);
            $user->setRoles($roles);

            $password = $this->hasher->hashPassword($user, $plainPassword);
            $user->setPassword($password);

            $manager->persist($user);
            $this->addReference('user'. $num, $user);
            $num++;
        }


        $manager->flush();
    }

    private function UserData()
    {
        return [
            ['jakis@dobry.pl', ['ROLE_USER'], 'kljsfdjklda'],
            ['nowy@dobry.pl', ['ROLE_USER'],';lkhsdad'],
            ['stary@dobry.pl', ['ROLE_USER'], 'ldafkhjd'],
            ['test@testowy.pl', ['ROLE_ADMIN'], 'testowy1'],
            ['test@stary.pl', ['ROLE_ADMIN'], 'stary1'],
            ['test@nowy.pl', ['ROLE_STOREMAN'], 'nowy1'],
            ['test@ladny.pl', ['ROLE_ADMIN'], 'ladny'],
            ['test@brzydki.pl', ['ROLE_STOREMAN'], 'brzydki'],

        ];
    }


}
