<?php

namespace App\Service\Front;

use App\Entity\User;
use App\Entity\Invoice;
use App\Entity\Sneaker;
use App\Repository\UserRepository;
use App\Repository\SneakerRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use Psr\Log\LoggerInterface;
use Stripe\StripeClient;
use App\Repository\InvoiceRepository;
use Symfony\Component\HttpFoundation\Request;

class SellerService
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private SneakerRepository $sneakerRepository,
        private InvoiceRepository $invoiceRepository

    ) {}
    
    public function updateSellerCapabilities($event): void
    {
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
                $user->setRoles($newRoles);
            }
        }else if($capabilities === 'active') { //Adding seller role if user is not yet granted of ROLE_SELLER
            if( !in_array('ROLE_SELLER', $user->getRoles()) ){
                $newRoles = $user->getRoles();
                $newRoles[] = 'ROLE_SELLER';
                $user->setRoles($newRoles);
            }
        }
        $this->entityManager->flush();
    }

    //Checks if a Stripe connected account has achieved its registration
    public function checkSellerCapabilities(User $user): bool
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
            return false;
        }catch(\Exception $exception){
            $this->logger->info($exception);
            return false;
        }
    }

    public function waitingActionFromSeller(User $user): bool
    {
        $invoices = $this->invoiceRepository->findUserInvoicesByStatus(Invoice::SOLD_STATUS, $user);
        if( $invoices ) return true;
        return false;
    }

    /*Checks if a user has finished the Stripe registration form 
    * and should be promoted to seller
    * or not and should fill the form
    */
    public function promoteOrRedirect(User $user, Request $request): ?String
    {
        if( in_array('ROLE_SELLER',  $user->getRoles()) ){
            return null;
        }

        $stripe = new StripeClient($_ENV['STRIPE_SK']);
        $account = null;

        if( $user->getStripeConnectId() ){
            /*  If the user has achieved the stripe form and still is not a seller
            *   promoting his role to Seller
            */
            if( $this->checkSellerCapabilities($user) ){
                $newRoles = $user->getRoles();
                $newRoles[] = 'ROLE_SELLER';
                $user->setRoles($newRoles);
                $this->entityManager->flush();
                return null;
            }
            $account = $user->getStripeConnectId();
        }else{
            $account = $stripe->accounts->create([
                'type' => 'express'
            ]);
            $account = $account->id;
            $user->setStripeConnectId($account);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        $localhost = $request->getHttpHost();
        $protocol = $request->getScheme();
        $url = "$protocol://$localhost";

        $link = $stripe->accountLinks->create([
            'account' => $account,
            'refresh_url' => "$url/account/become_seller",
            'return_url' => "$url/account",
            'type' => 'account_onboarding',
        ]);
        
        return $link->url;
    }

}