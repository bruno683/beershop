<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class CartController extends AbstractController
{
    /**
     * @Route("cart", name = "cart")
     *
     * @param Request $request
     * @param ProductsRepository $productRepo
     * @return Response
     */
    public function index( Request $request, ProductsRepository $productRepo): Response
    {
        $session = $request->getSession();
        $cart = $session->get('panier', []);

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'produit' => $productRepo->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach ($cartWithData as $item){
            $totalItem = $item['produit']->getPrice() / 100 * $item['quantity'];
            $total += $totalItem;
        }

      
        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'total' => $total,
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name = "cart_add")
     */
    public function add($id, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
           $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }


        $session->set('panier', $panier);

        return $this->redirectToRoute('cart');
    }



    /**
     * @Route("/cart/remove/{id}", name="cart_remove")
     */
    public function removeItem($id, Request $request)
    {
        $session = $request->getSession();

        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
          $panier[$id]--;
        }

        if (!$panier[$id] > 0) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('cart');
    }
    

}
