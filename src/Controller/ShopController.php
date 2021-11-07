<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'shop')]
    public function index(ProductsRepository $productsRepository): Response
    {
        $items = $productsRepository->findAll(); 
        

        return $this->render('shop/index.html.twig', [
            'title' => 'Notre catalogue',
            'items' => $items
        ]);
    }
}
