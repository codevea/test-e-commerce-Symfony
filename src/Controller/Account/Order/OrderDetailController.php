<?php

namespace App\Controller\Account\Order;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderDetailController extends AbstractController
{
    #[Route('/profil/mon-compte/detail-de-ma-commande-{id}', name: 'app_account_order_detail')]
    public function index($id, OrderRepository $orderRepository): Response
    {
        $order = $orderRepository->findOneBy([
          'id' => $id,
          'user' => $this->getUser()
        ]);

        return $this->render('account/order/orderDetail.html.twig', [
            'order' => $order,
        ]);
    }
}