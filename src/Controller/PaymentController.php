<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(Request $request, ProductsRepository $productRepo): Response
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
        
        
       return $this->render('payment/index.html.twig', [
            'title' => 'Paiement'
            
        ]);
    }

    /**
     * @Route("/checkout", name = "payment_checkout")
     */
    public function checkout(Request $request, ProductsRepository $productRepo)
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
        
        \Stripe\Stripe::setApiKey('sk_test_51Js7GtL81yHRap8c46xs7Iu5XyBO4TRb7jxByndtFXLQvYCG6INLc3Ixlz3U0QCojWIp2gnoCoaf26hTyo7ejE4V00X29Wt8EO');

        $stripeSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
              'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                  'name' => 'T-shirt',
                ],
                'unit_amount' => $total,
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => 'https://example.com/success',
            'cancel_url' => 'https://example.com/cancel',
          ]);

          dd($stripeSession);
    }
}
