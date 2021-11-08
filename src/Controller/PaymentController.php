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
        if (isset($_POST[$total]) && !empty($_POST[$total])) {
            require_once('vendor/autoload.php');

            \Stripe\Stripe::setApiKey($_ENV('STRIPE_SECRET_KEY'));

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $total,
                'currency' => 'eur'
            ]);
            dd($intent);
        } 
        
        
        return $this->render('payment/index.html.twig', [
            'title' => 'Paiement'
            
        ]);
    }
}
