<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SessionVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'VIEW', 'DELETE'])
            && $subject instanceof \App\Entity\Sessions;
    }

    protected function voteOnAttribute($attribute, $sessions, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($sessions->getEcole()->getManager()->getId() == null){
            return false;
        }


        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'DELETE':
                return $sessions->getEcole()->getManager()->getId() == $user->getId();
                break;
            case 'EDIT':
                return $sessions->getEcole()->getManager()->getId() == $user->getId();
                break;
            case 'VIEW':
                return $sessions->getEcole()->getManager()->getId() == $user->getId();
                break;
        }

        return false;
    }
}
