<?php

namespace App\Controller\Account\Order;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderAccountController extends AbstractController
{
    #[Route('/commande/mon-compte/mes-commandes', name: 'app_account_order_account')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([
            'user' => $this->getUser(),
            'state' => [2,3]
        ]);

        return $this->render('account/order/orderAccount.html.twig', [
            'orders' => $orders,
        ]);
    }
}
