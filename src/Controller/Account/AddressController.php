<?php

namespace App\Controller\Account;

use App\classe\Cart;
use App\Entity\Address;
use App\Form\AddressForm;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AddressController extends AbstractController
{
    #[Route('/profil/mes-adresses', name: 'app_account_addresses_list')]
    public function adress(): Response
    {
        return $this->render('account/address/addresses-list-account.html.twig');
    }

    #[Route('/profil/adresse/supprimer/{id}', name: 'app_account_address_delete')]
    public function delete(?int $id, AddressRepository $addressRepository, EntityManagerInterface $entityManagerInterface): Response
    {
        // Récupérer l'adresse enregistrée.
        $address = $addressRepository->findOneById($id);

        // Verifie que l'adresse existe et que ID User de l'adresse correspont à l'ID de l'utilisateur
        if (!$address or $address->getUser() != $this->getUser()) {
            $this->redirectToRoute('app_account_addresses_list');
        }

        $entityManagerInterface->remove($address);
        $entityManagerInterface->flush();

        $this->addFlash('success', 'Votre adresse à bien été supprimer');
        return $this->redirectToRoute('app_account');
    }

    /**
     *
     * Permet de créer ou modifier une adresse : 
     * accepte l'ID d'une adresse dans l'URL ou accepte une route sans paramètre ID dans l'URL.
     *
     * Allows creating or modifying an address:
     * Accepts the ID of an address in the URL or accepts a route without an ID parameter in the URL.
     */
    #[Route('/profil/adresse/{id}', name: 'app_account_address_form', defaults: ['id' => null])]
    public function adresses(?int $id, Request $request, Cart $cart, EntityManagerInterface $entityManagerInterface, AddressRepository $addressRepository): Response
    {
        if ($id) {
            // Récupérer pour modifier une adresse déjà enregistrée.
            $address = $addressRepository->findOneById($id);
            // Verifie que l'adresse existe et que ID User de l'adresse correspont à l'ID de l'utilisateur
            if (!$address or $address->getUser() != $this->getUser()) {
                $this->redirectToRoute('app_account_addresses_list');
            }
        } else {
            $address = new Address();
            // Récupérer l'ID de l'utilisateur et le définir dans l'entité Address
            $address->setUser($this->getUser());
            
        }

        $form = $this->createForm(AddressForm::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManagerInterface->persist($address);
            $entityManagerInterface->flush();

            if ($cart->allCountQuantityProduct() > 0) {
                $this->addFlash('info', 'Votre adresse a bien été enregistrée');
                return $this->redirectToRoute('app_order');
            }
            $this->addFlash('success', 'Votre adresse a bien été sauvegardée');
            return $this->redirectToRoute('app_account_addresses_list');
        }

        return $this->render('account/address/address-form-account.html.twig', [
            'form' => $form,
        ]);
    }
}
