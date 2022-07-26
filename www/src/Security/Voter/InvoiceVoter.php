<?php

namespace App\Security\Voter;
use App\Entity\Invoice;
use App\Entity\User;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class InvoiceVoter extends Voter
{
    public const VIEW = 'INVOICE_VIEW';
    public const VIEW_AS_SELLER = 'INVOICE_VIEW_AS_SELLER';
    public const REPORT_AS_RECEIVED = 'INVOICE_REPORT_AS_RECEIVED';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::REPORT_AS_RECEIVED, self::VIEW, self::VIEW_AS_SELLER])
            && $subject instanceof Invoice;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::VIEW:
                if($subject->getBuyer() !== $user) {
                    return false;
                }
                return true;
                break;

            case self::VIEW_AS_SELLER:
                if($subject->getSneaker()->getPublisher() !== $user) {
                    return false;
                }
                return true;
                break;
                
            case self::REPORT_AS_RECEIVED:
                return $this->canReportAsReceived($subject, $user);

        }

        return false;
    }

    private function canReportAsReceived(Invoice $subject, User $user): bool
    {
        return $subject->getBuyer() === $user;
    }

}
