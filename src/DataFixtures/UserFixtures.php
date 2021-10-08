<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Traits\HasPasswordHasher;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    use HasPasswordHasher;

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setEmail("1234@gmail.com");
        $user->setUsername("Michael Maxwell2");
        $user->setRoles(["ROLE_USER"]);
        $user->setStatus("Online");
        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'the_new_password'
        ));
        $manager->persist($user);
        $manager->flush();
    }
}
