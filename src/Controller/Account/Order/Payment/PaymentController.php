<?php

namespace App\Controller\Account\Order\Payment;

use Stripe\Stripe;
use App\classe\Cart;
use Stripe\Checkout\Session;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PaymentController extends AbstractController
{
    #[Route('/commande/paiement-{id}', name: 'app_payment', schemes: ['https'])]
    public function index($id, OrderRepository $orderRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        // Vérifie et recupére la commande appartient bien à l'utilisateur
        $order = $orderRepository->findOneById([
            'id' => $id,
            'user' => $this->getUser()
        ]);
        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
        // Fin -----------------------------------------

        $product_for_stripe = [];

        // Récupère les produits de la commande
        foreach ($order->getOrderDetails() as $product) {

            $product_for_stripe[] = [

                'price_data' => [
                    'currency' => 'EUR',
                    'unit_amount' => $product->getProductOrderDetailTtc(),
                    'product_data' => [
                        'name' => $product->getProductName(),
                        'images' => [
                            $_ENV['DOMAIN'] . '/uploads/' . $product->getProductIllustration(),
                        ]
                    ]
                ],
                'quantity' => $product->getProductQuantity(),
            ];
        }

        // Récupère le transporteur
        $product_for_stripe[] = [
            'price_data' => [
                'currency' => 'EUR',
                // 'local' => ['fr'],
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => 'Transporteur : ' . $order->getCarrierName(),
                ],
            ],
            'quantity' => 1,

        ];



        $checkout_session = Session::create([
            // Récupère l'e-mail de l'utilisateur
            'customer_email' => $this->getUser()->getEmail(),
            // fin ---------------------------
            'line_items' => [[
                $product_for_stripe
            ]],
            'mode' => 'payment',

            'success_url' => $_ENV['DOMAIN'] . '/commande/valide/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['DOMAIN'] . '/mon-panier/retour',
        ]);

        $order->setStripeSessionId($checkout_session->id);
        $entityManagerInterface->flush();

        return $this->redirect($checkout_session->url);
    }


    #[Route('/commande/valide/{stripe_session_id}', name: 'app_payment_success', schemes: ['https'])]
    public function success($stripe_session_id, OrderRepository $orderRepository,  EntityManagerInterface $entityManagerInterface, Cart $cart)
    {
        // Vérifie et recupére l'utilisateur qui à passer la commande
        $order = $orderRepository->findOneBy([
            'stripe_session_id' => $stripe_session_id,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            return $this->redirectToRoute('app_home');
        }
        // Fin -----------------------------------------

        if ($order->getState() == 1) {
            $order->setState(2);
            $cart->removeCart();// Vide la panier
            $entityManagerInterface->flush();
        }

        return $this->render('account/order/success.html.twig', [
            'order' => $order
        ]);
    }
}
