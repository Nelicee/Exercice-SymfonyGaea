<?php

namespace App\Service;

use App\Entity\User;

class UserService
{
    public function AgeCalculation(User $user) 
    {
        
            $now = new \DateTime();
            $birthDate = $user->getBirthDate();
            return $birthDate->diff($now)->y;
        

    }
}
