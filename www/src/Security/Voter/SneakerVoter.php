<?php

namespace App\Security\Voter;

use App\Entity\Sneaker;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Service\Front\SellerService;
use App\Service\SneakerServiceGlobal;

class SneakerVoter extends Voter
{
    const EDIT = 'SNEAKER_EDIT';
    const BUY_FROM_MP = 'SNEAKER_BUY_MP';
    const BUY_FROM_SHOP = 'SNEAKER_BUY_SHOP';
    const DELETE = 'SNEAKER_DELETE';

    public function __construct(
        private SellerService $sellerService,
        private SneakerServiceGlobal $sellerServiceGlobal
    ) {}

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::BUY_FROM_MP, self::BUY_FROM_SHOP ])
            && $subject instanceof Sneaker;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        
        switch ($attribute) {
            case self::BUY_FROM_SHOP:
                return $this->canBuyFromShop($subject, $user );
                break;

            case self::BUY_FROM_MP:
                return $this->canBuyFromMP($subject, $user, $this->sellerService );
                break;

            case self::EDIT:
                return $this->canEdit($subject, $user);
                break;
            
            case self::DELETE:
                return in_array('ROLE_ADMIN', $user->getRoles()) || $this->canEdit($subject, $user);
                break;
            
        }

        return false;
    }

    /**
     * @param Sneaker $subject
     * @param User $user
     * @return bool
     */
    private function canEdit(Sneaker $subject, User $user): bool {
        return $subject->getPublisher() === $user;
    }

    /**
     * @param Sneaker $subject
     * @param User $user
     * @param SellerService
     * @return bool
     */
    private function canBuyFromMP(
                                Sneaker $subject,
                                User $user,
                                SellerService $sellerService
    ): bool {
        if( $subject->getPublisher() === $user
            || $subject->getFromShop()
            || $subject->getSold() === true
            || !$subject->getStripeProductId() 
            //|| !$sellerService->checkSellerCapabilities($subject->getPublisher() )
        ){
            return false;
        }

        return true;
    }

    /**
     * @param Sneaker $subject
     * @param User $user
     * @return bool
     */
    private function canBuyFromShop(
                                Sneaker $subject, 
                                User $user
    ): bool {
        if( !$subject->getFromShop()
            || $subject->getSold() === true
            || !$subject->getStripeProductId()
            || $subject->getStock() < 1
        ){
            return false;
        }

        return true;
    }

}
