<?php

namespace App\Controller\Account\Order;

use Dompdf\Dompdf;
use App\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// https://github.com/dompdf/dompdf
// https://github.com/nucleos/NucleosDompdfBundle
final class PdfController extends AbstractController
{
    #[Route('/profil/mon-compte/document-pdf-{id}', name: 'app_pdf_user')]
    public function userPdf($id, OrderRepository $orderRepository): Response
    {
        $dompdf = new Dompdf();

        $order = $orderRepository->findOneBy([
            'id' => $id,
            'user' => $this->getUser()
        ]);

        if (!$order) {
            return $this->redirectToRoute('app_account');
        }
        if ($order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account');
        }

        $html = $this->renderView('account/order/pdf/pdf.html.twig', ['order' => $order,]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('document.pdf', [
            'Attachment' =>  false
        ]);

        exit();
    }


    #[Route('/document-pdf-{id}', name: 'app_pdf_admin')]
    public function adminPdf($id, OrderRepository $orderRepository): Response
    {
        $dompdf = new Dompdf();

        $order = $orderRepository->findOneBy([
            'id' => $id,
        ]);

        if (!$order) {
            return $this->redirectToRoute('admin');
        }

        $html = $this->renderView('account/order/pdf/pdf.html.twig', ['order' => $order,]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('document.pdf', [
            'Attachment' =>  false
        ]);

        exit();
    }
}
