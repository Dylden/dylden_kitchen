<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminUserController extends AbstractController
{
    #[Route('/admin/logout', 'logout')]
    public function logout()
    {
//Route utilisée par symfony
        //dans le security.yaml
        //Pour gérer la déconnexion
    }

    #[Route('/admin/create_user', 'create_user')]
    public function createUser(UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager)
    {

        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $password = $form->get('password')->getData();

            $hashedPassword = $passwordHasher->hashPassword($user, $password);

            $user->setPassword($hashedPassword);


            //Je récupère le choix du rôle venant du formulaire
            $role = $form->get('roles')->getData();

            //Le rôle sélectionné sera attribué à l'utilisateur
            $user->setRoles($role);


            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été recruté chef !");


            return $this->redirectToRoute('homepage');
        }

        $form_view = $form->createView();

        return $this->render('admin_create/create_user.html.twig', [
            'form_view' => $form_view,
        ]);
    }
}

