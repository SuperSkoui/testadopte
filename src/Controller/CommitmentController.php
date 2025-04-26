<?php

namespace App\Controller;

use App\Entity\Commitment;
use App\Form\CommitmentType;
use App\Repository\CommitmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/commitment')]
final class CommitmentController extends AbstractController
{
    #[Route(name: 'commitment.index', methods: ['GET'])]
    public function index(CommitmentRepository $commitmentRepository): Response
    {
        return $this->render('commitment/index.html.twig', [
            'commitments' => $commitmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'commitment.new', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $commitment = new Commitment();
        $form = $this->createForm(CommitmentType::class, $commitment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commitment);
            $entityManager->flush();

            return $this->redirectToRoute('commitment.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commitment/new.html.twig', [
            'commitment' => $commitment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'commitment.show', methods: ['GET'])]
    public function show(Commitment $commitment): Response
    {
        return $this->render('commitment/show.html.twig', [
            'commitment' => $commitment,
        ]);
    }

    #[Route('/{id}/edit', name: 'commitment.edit', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function edit(Request $request, Commitment $commitment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommitmentType::class, $commitment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('commitment.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commitment/edit.html.twig', [
            'commitment' => $commitment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'commitment.delete', methods: ['POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function delete(Request $request, Commitment $commitment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commitment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($commitment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commitment.index', [], Response::HTTP_SEE_OTHER);
    }

}
