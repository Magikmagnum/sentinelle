<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TuteurVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof \App\Entity\Tuteurs;
    }

    protected function voteOnAttribute($attribute, $tuteur, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'VIEW':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'DELETE':
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
