<?php
namespace App\Controller;

use App\Repository\RecipeRepository;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class PublicRecipeController extends AbstractController
{

    #[Route('/recipes', 'list_recipes')]
    public function listPublishedRecipes(RecipeRepository $recipeRepository){

        $recipes = $recipeRepository->findBy(['isPublished' => true]);
        return $this->render('public_recipe/list.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recipes/{id}', 'show_recipe')]
    public function showRecipe(int $id, RecipeRepository $recipeRepository){

        $recipe = $recipeRepository->find($id);

        if(!$recipe || $recipe->isPublished()){
            $notFoundResponse = new Response('Recette non trouvée', 404);
            return $notFoundResponse;
        }

        return $this->render('public_recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }
}