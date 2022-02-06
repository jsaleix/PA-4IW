<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Stripe\StripeClient;

class UserFixtures extends Fixture
{
    const USER_ADMIN = 'admin';
    const USER_SELLER = 'seller';
    const USER_USER = 'user';

    /** @var UserPasswordHasherInterface $userPasswordHasher */
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = (new User())
            ->setEmail('admin@admin')
            //->setIsVerified(true)
            ->setRoles(['ROLE_ADMIN'])
        ;
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'test'));
        $manager->persist($admin);
        $this->setReference(self::USER_ADMIN, $admin);

        $user = (new User())
            ->setEmail('user@user')
            //->setIsVerified(true)
            ->setRoles(['ROLE_USER'])
        ;
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'test'));
        $manager->persist($user);
        $this->setReference(self::USER_USER, $user);

        $stripe = new StripeClient($_ENV['STRIPE_SK']);
        $stripeAccount = $stripe->accounts->create([
            'type' => 'express'
        ]);
        $stripeAccount = $stripeAccount->id;

        $seller = (new User())
            ->setEmail('seller@seller')
            //->setIsVerified(true)
            ->setRoles(['ROLE_SELLER'])
            ->setStripeConnectId($stripeAccount)
        ;
        $seller->setPassword($this->userPasswordHasher->hashPassword($seller, 'test'));
        $manager->persist($seller);
        $this->setReference(self::USER_SELLER, $seller);

        $manager->flush();
    }
}
