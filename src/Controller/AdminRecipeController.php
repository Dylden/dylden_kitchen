<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\AdminRecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminRecipeController extends AbstractController
{
    #[Route('/admin/recipe/create', name: 'admin_create_recipe')]
    public function createRecipe(Request $request, EntityManagerInterface $entityManager, ParameterBagInterface $parameterBag): Response
    {

        $recipes = new Recipe();

        $form = $this->createForm(AdminRecipeType::class, $recipes);

        //Le handleRequest récupère les donées de POST (donc du form envoyé)
        //pour chacune, il va modifier l'entité (setTitle, setImage etc.)
        //et donc remplir l'entité avec les données du form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            //je récupère le fichier envoyé dans le champs image du formulaire
            $recipeImage = $form->get('image')->getData();

            //s'il y a bien une image envoyée
            if ($recipeImage) {

                //je génère un nom unique pour l'image, en gardant l'extension
                //originale (.jpeg, .png etc)
                $imageNewName = uniqid() . '.' . $recipeImage->guessExtension();

                //je récupère grâce à la classe
                $rootDir = $parameterBag->get('kernel.project_dir');
                $uploadsDir = $rootDir . '/public/assets/uploads';

                $recipeImage->move($uploadsDir, $imageNewName);

                $recipes->setImage($imageNewName);

            }

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

    #[Route('/admin/recipe/{id}/delete', name: 'admin_delete_recipe', requirements: ['id' => '\d+'])]
    public function deleteRecipe(int $id, RecipeRepository $recipeRepository, EntityManagerInterface $entityManager): Response
    {

        $recipe = $recipeRepository->find($id);

        $entityManager->remove($recipe);
        $entityManager->flush();

        $this->addFlash('success', 'la recette a été royalement supprimé monseigneur !');
        return $this->redirectToRoute('admin_list_recipe');

    }

    #[Route('/admin/recipe/{id}/update', name: 'admin_update_recipe', requirements: ['id' => '\d+'])]
    public function updateRecipe(int $id, Request $request, ParameterBagInterface $parameterBag, RecipeRepository $recipeRepository, EntityManagerInterface $entityManager): Response
    {

        $recipes = $recipeRepository->find($id);

        $form = $this->createForm(AdminRecipeType::class, $recipes);

        //Le handleRequest récupère les donées de POST (donc du form envoyé)
        //pour chacune, il va modifier l'entité (setTitle, setImage etc.)
        //et donc remplir l'entité avec les données du form
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            //je récupère le fichier envoyé dans le champs image du formulaire
            $recipeImage = $form->get('image')->getData();

            //s'il y a bien une image envoyée
            if ($recipeImage) {

                //je génère un nom unique pour l'image, en gardant l'extension
                //originale (.jpeg, .png etc)
                $imageNewName = uniqid() . '.' . $recipeImage->guessExtension();

                //je récupère grâce à la classe
                $rootDir = $parameterBag->get('kernel.project_dir');
                $uploadsDir = $rootDir . '/public/assets/uploads';

                $recipeImage->move($uploadsDir, $imageNewName);

                $recipes->setImage($imageNewName);

            }

            $entityManager->persist($recipes);
            $entityManager->flush();

            $this->addFlash('success', 'La recette a été noblement mise à jour mon Roi');
        }

        $form_view = $form->createView();

        return $this->render('admin_recipe/update.html.twig', [
            'form_view' => $form_view,
            'recipes' => $recipes
        ]);
    }
}
