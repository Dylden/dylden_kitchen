<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminListController extends AbstractController
{
    #[Route('/admin/list', name: 'admin_list')]
    public function index(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin_list/index.html.twig', [
            'user' => $users
        ]);
    }
}
