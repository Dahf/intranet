<?php

namespace App\Traits;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

trait HasPasswordHasher
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @required
     */
    public function setPasswordHasher(UserPasswordHasherInterface $passwordHasher): void
    {
        $this->passwordHasher = $passwordHasher;
    }
}