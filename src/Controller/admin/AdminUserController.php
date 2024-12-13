<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AbstractController
{
    #[Route('/admin/logout', 'logout')]
    public function logout()
    {
//Route utilisée par symfony
        //dans le security.yaml
        //Pour gérer la déconnexion
    }

    #[Route('/admin/list', name: 'admin_list')]
    public function index(UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'users' => $users
        ]);
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

        return $this->render('admin/create_user.html.twig', [
            'form_view' => $form_view,
        ]);
    }

    #[Route('/admin/user/{id}/delete', name: 'admin_delete_user', requirements: ['id' => '\d+'])]
    public function deleteUser(int $id, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {

        $user = $userRepository->find($id);

        //Si on tente de supprimer l'utilisateur connecté, renvoie un message d'erreur
        if ($user->getId() === $this->getUser()->getId()) {
            $this->addFlash('success', "Impossible de vous supprimer chef, ça n'a aucun sens chef !");
            return $this->redirectToRoute('admin_list');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', "L'utilisateur a été envoyé à la dérive chef !");

        return $this->redirectToRoute('admin_list');
    }

    #[Route('/admin/user/{id}/update', name: 'admin_update_user', requirements: ['id' => '\d+'])]
    public function updateUser(int $id, UserPasswordHasherInterface $passwordHasher, Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {

        $user = $userRepository->find($id);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $password = $form->get('password')->getData();


            if (!$password) {
                $user->setPassword($user->getPassword());
            } else {
                $hashedPassword = $passwordHasher->hashPassword($user, $password);
                $user->setPassword($hashedPassword);
            }

            //Je récupère le choix du rôle venant du formulaire
            $role = $form->get('roles')->getData();

            //Le rôle sélectionné sera attribué à l'utilisateur
            $user->setRoles($role);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été mis à niveau chef !");

            return $this->redirectToRoute('homepage');
        }

        $form_view = $form->createView();

        return $this->render('admin/update_user.html.twig', [
            'form_view' => $form_view,
            'user' => $user
        ]);
    }
}
