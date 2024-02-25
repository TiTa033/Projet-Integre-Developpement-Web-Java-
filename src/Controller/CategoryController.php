<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Categories;
use App\Entity\CategoriesRepository;
use App\Entity\AnnonceRepository;
use App\Entity\Annonces;
use App\Entity\Comment;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[Route('/category/{id}', name: 'app_showAnnonceParCategory')]
    public function showAnnonceParCategorie(Categories $category): Response
    {
        $annonces=$category->getAnnonces();
        
        return $this->render('category/showannonces.html.twig', [
            'category'=> $category,
            'annonces'=>$annonces,
        ]);
    }
}
