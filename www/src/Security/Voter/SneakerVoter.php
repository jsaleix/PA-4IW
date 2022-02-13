<?php

namespace App\Security\Voter;

use App\Entity\Sneaker;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SneakerVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
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
            case self::EDIT:
                return $this->canEdit($subject, $user);
                break;
            case self::DELETE:
                return in_array($user->getRoles(), 'ROLE_ADMIN') || $this->canEdit($subject, $user);
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

}