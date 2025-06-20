<?php

namespace App\Controller\Account\Order;

use App\classe\Cart;
use App\Entity\Order;
use App\Form\OrderForm;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Tunnel d'achat
 */
final class OrderController extends AbstractController
{
    /**
     * Choix de l'adresse de livraison et du transporteur
     *
     * @return Response
     */
    #[Route('/commande', name: 'app_order')]
    public function commande(): Response
    {
        $addresses = $this->getUser()->getAddresses();

        if (count($addresses) == 0) {
            return $this->redirectToRoute('app_account_address_form');
        }

        $form = $this->createForm(OrderForm::class, null, [
            'addresses' => $addresses,
            // Méthode generateUrl(): Indique la redirection si le formulaire est soumis.
            // Redirection vers la route récapitulatif de la commande (submit)
            'action' => $this->generateUrl('app_order_summary')
        ]);

        return $this->render('account/order/commande.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * Récapitulatif de la commande et enregistrement dans la base de données (BDD)
     * Redirection vers le paiement
     *
     * @return Response
     */
    #[Route('/commande/recapitulatif-commande', name: 'app_order_summary', schemes: ['https'])]
    public function summary(Request $request, EntityManagerInterface $entityManagerInterface, Cart $cart): Response
    {
        $products = $cart->getCart();

        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }

        $form = $this->createForm(OrderForm::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $objAddress = $form->get('address')->getData();

            $address = $objAddress->getFirstname() . ' ' . $objAddress->getLastname() . ' <br>';
            $address .= $objAddress->getAddress() . ' <br>';
            $address .= $objAddress->getPostal() . ' ' . $objAddress->getCity() . ' ' . $objAddress->getCountry() . ' <br>';
            $address .= $objAddress->getPhone();

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt(new \DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carrier')->getData()->getName());
            $order->setCarrierPrice($form->get('carrier')->getData()->getPrice());
            $order->setDelivery($address);

            foreach ($products as $product) {
                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductIllustration($product['object']->getIllustration());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['quantity']);
                $order->addOrderDetail($orderDetail);
            }

            $entityManagerInterface->persist($order);
            $entityManagerInterface->flush();
        }


        return $this->render('account/order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'order' => $order,
            'allPriceTtc' => $cart->allPriceTtc(),
            'allPriceHt' => $cart->allPriceHt()
        ]);

    }
}
