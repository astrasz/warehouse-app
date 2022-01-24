<?php 

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

class UserChecker implements UserCheckerInterface
{    
    
    public function checkPreAuth(UserInterface $user): void
    {
        if(!$user instanceof User)
        {
            return;
        }

    }

    public function checkPostAuth(UserInterface $user): void
    {
        if($user->getIsEnabled() === false)
        {
            throw new CustomUserMessageAccountStatusException('You do not have access. Contact your admin for more info.');
        }

    }
}