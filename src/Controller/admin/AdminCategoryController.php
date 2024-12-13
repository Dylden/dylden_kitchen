<?php

namespace App\Controller\admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminCategoryController extends AbstractController
{
    #[Route('/categories', name: 'categories')]
    public function index(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = $categoryRepository->findAll();

        $user = $this->getUser();

        return $this->render('admin/dashboard.html.twig', [
            'categories' => $categories,
            'user' => $user,
        ]);
    }

    #[Route('admin/categories', name: 'admin_list_categories')]
    public function listAdminCategories(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $categories = $categoryRepository->findAll();

        $user = $this->getUser();

        return $this->render('admin/categories/list_categories.html.twig', [
            'categories' => $categories,
            'user' => $user,
        ]);
    }

    #[Route('/admin/category_create', name: 'admin_create_category')]
    public function createCategory(Request $request, EntityManagerInterface $entityManager): Response{
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('admin_list_categories');
        }

        $form_view = $form->createView();

        $user = $this->getUser();

        return $this->render('admin/categories/create_category.html.twig', [
            'form_view' => $form_view,
            'user' => $user,
        ]);
    }

    #[Route('/admin/categories/{id}/update', name: 'admin_update_category', requirements: ['id' => '\d+'])]
    public function updateCategory(Request $request, Category $category, EntityManagerInterface $entityManager): Response{


        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $entityManager->flush();
            return $this->redirectToRoute('admin_list_categories');
        }

        $form_view = $form->createView();

        $user = $this->getUser();

        return $this->render('admin/categories/update_category.html.twig', [
            'form_view' => $form_view,
            'categories' => $category,
            'user' => $user,
        ]);
    }

    #[Route('/admin/categories/{id}/delete', name: 'admin_delete_category', requirements: ['id' => '\d+'])]
    public function deleteCategory(int $id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {

        $category = $categoryRepository->find($id);

        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success', 'la catégorie a été royalement supprimé monseigneur !');
        return $this->redirectToRoute('admin_list_categories');

    }

    public function recipesByCategory(){

    }
}


