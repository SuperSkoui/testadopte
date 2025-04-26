<?php

namespace App\Controller;

use App\Entity\Payment;
use App\Form\PaymentType;
use App\Repository\PaymentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/payment')]
#[isGranted('ROLE_ADMIN')]
final class PaymentController extends AbstractController
{
    #[Route(name: 'app_payment_index', methods: ['GET'])]
    public function index(PaymentRepository $paymentRepository): Response
    {
        return $this->render('payment/index.html.twig', [
            'payments' => $paymentRepository->findAll(),
        ]);
    }

    public function getCA(PaymentRepository $paymentRepository,\DateTimeImmutable $start, \DateTimeImmutable $end): Response{
        /*
         * faire le front
         * */
        return $this->render('payment/ca.html.twig', [
            'payments' => $paymentRepository->getCAFromDateToDate($start, $end),
        ]);
    }

}
