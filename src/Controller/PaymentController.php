<?php

namespace App\Controller;

use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
    public function checkout(Request $request, ProductsRepository $productRepo, $stripeSk): Response
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
        
        \Stripe\Stripe::setApiKey($stripeSk);

        $stripeSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
              'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                  'name' => 'Bouteilles de Biéres',
                ],
                'unit_amount' => $total*100,
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);

          return $this->redirect($stripeSession->url, 303);
    }

    /**
     * @Route("/success-url", name="successurl")
     */
    public function successUrl()
    {
        return $this->render('payment/success.html.twig');
    }
    
    /**
     * @Route("/cancel-url", name="cancelurl")
     */
    public function cancelUrl()
    {
        return $this->render('payment/cancel.html.twig');
    }
}
