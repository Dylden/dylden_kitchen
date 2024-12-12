<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminUserController extends AbstractController
{
    #[Route('/admin/logout', 'logout')]
    public function logout(){
//Route utilisée par symfony
        //dans le security.yaml
        //Pour gérer la déconnexion
    }
}

