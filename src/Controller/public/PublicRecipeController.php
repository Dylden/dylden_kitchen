<?php
namespace App\Controller\public;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class PublicRecipeController extends AbstractController
{

    #[Route('/recipes', 'list_recipes')]
    public function listPublishedRecipes(RecipeRepository $recipeRepository){

        $recipes = $recipeRepository->findBy(['isPublished' => true]);
        return $this->render('public/recipes/list_recipes.html.twig', [
            'recipes' => $recipes
        ]);
    }

    #[Route('/recipes/{id}', 'show_recipe', requirements: ['id' => '\d+'])]
    public function showRecipe(int $id, RecipeRepository $recipeRepository){

        $recipe = $recipeRepository->find($id);

        if(!$recipe || !$recipe->isPublished()){
            $notFoundResponse = new Response('Recette non trouvée', 404);
            return $notFoundResponse;
        }

        return $this->render('public/recipes/show_recipe.html.twig', [
            'recipe' => $recipe
        ]);
    }


    #[Route('recipes/search', 'search_recipes')]
    public function searchRecipes(Request $request, RecipeRepository $recipeRepository){

        $search = $request->get('search');

        //dd("jusqu'ici tout va bien chef");

        $recipes = $recipeRepository->findBySearchInTitle($search);

        return $this->render('public/recipes/search_recipe.html.twig', [
            'recipes' => $recipes,
            'search' => $search
        ]);
    }
}