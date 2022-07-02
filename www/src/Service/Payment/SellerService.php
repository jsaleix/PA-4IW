<?php

namespace App\Service\Payment;

use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\Sneaker;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Log\LoggerInterface;
use Stripe\StripeClient;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SellerService
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger
    ) {}

    public function updateSellerCapabilities($event){
        $this->logger->info('updateSellerCapabilities');

        $account = $event['account'];
        $capabilities = $event['data']['object']['capabilities']['transfers'];

        $user = $this->userRepository->findOneBy(['stripeConnectId' => $account]);
        if(!$user) throw new \Exception('Account not found');

        if($capabilities === 'inactive'){ //Removing seller role from user if the user is a seller and transfers capability is disabled
            if( in_array('ROLE_SELLER', $user->getRoles()) ){
                $newRoles = array_filter($user->getRoles(), static function ($el){
                    return $el !== 'ROLE_SELLER';
                });
                $this->logger($newRoles);
                //$user->setRoles($newRoles);
            }
        }else if($capabilities === 'active') { //Adding seller role if user is not yet granted of ROLE_SELLER
            if( !in_array('ROLE_SELLER', $user->getRoles()) ){
                $user->setRoles('ROLE_SELLER');
            }
        }
        $this->entityManager->flush();
    }

    public function checkSellerCapabilities(User $user)
    {
        try{
            $this->logger->info('CheckSellerCapabilities');
            $stripe = new StripeClient($_ENV['STRIPE_SK']);
            $account = $stripe->accounts->retrieve( $user->getStripeConnectId(), []);
            if(!$account) throw new \Exception('No Stripe account found');

            $capabilities = $account->capabilities;
            if($capabilities->transfers === 'inactive'){
                throw new \Exception('Capabilities inactive');
            }else if ($capabilities->transfers === 'active'){
                return true;
            }

        }catch(\Exception $exception){
            $this->logger->info($exception);
            return false;
        }
    }

}