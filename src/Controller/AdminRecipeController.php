<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\AdminRecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminRecipeController extends AbstractController
{
    #[Route('/admin/recipe/create', name: 'admin_create_recipe')]
    public function createRecipe(Request $request, EntityManagerInterface $entityManager): Response
    {

        $recipes = new Recipe();

        $form = $this->createForm(AdminRecipeType::class, $recipes);


        //Le handleRequest récupère les donées de POST (donc du form envoyé)
        //pour chacune, il va modifier l'entité (setTitle, setImage etc.)
        //et donc remplir l'entité avec les données du form
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->persist($recipes);
            $entityManager->flush();
            $this->addFlash('success', 'La recette a été majestueusement ajouté messire !');


            return $this->redirectToRoute('admin_create_recipe');
        }

        $form_view = $form->createView();

        return $this->render('admin_recipe/create.html.twig', [
            'form_view' => $form_view,
        ]);
    }
        #[Route('/admin/recipe/list', name: 'admin_list_recipe')]
        public function listRecipe(RecipeRepository $recipeRepository, EntityManagerInterface $entityManager): Response
        {
            $recipes = $recipeRepository->findAll();

            return $this->render('admin_recipe/list.html.twig', [
                'recipe' => $recipes
            ]);
        }
}
