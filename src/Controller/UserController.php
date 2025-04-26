<?php

namespace App\Controller;

use App\Entity\Commitment;
use App\Entity\User;
use App\Form\CommitmentType;
use App\Form\UserType;
use App\Repository\CommitmentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]

final class UserController extends AbstractController
{
    #[Route(name: 'user.index', methods: ['GET'])]
    #[isGranted('ROLE_ADMIN')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAllData();
        //dd($users);
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/new', name: 'user.new', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user.index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user.show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'user.edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'user.delete', methods: ['POST'])]
    #[isGranted('ROLE_ADMIN')]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user.index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('{id}/add/{idCommitment}', name: 'user.add.commitment', methods: ['GET', 'POST'])]
    public function addCommitment(Request $request, EntityManagerInterface $entityManager, User $user,int $idCommitment, CommitmentRepository $commitmentRepository ): Response
    {

        $user->setCommitment($commitmentRepository->find($idCommitment));
        $user->setCommitmentActive(true);
        $user->setCommitmentStart(new \DateTimeImmutable());
        $entityManager->persist($user);
        $entityManager->flush();
        /*
         * call api pour 1er reglement
         * */

        return $this->render('commitment/index.html.twig',[
            'commitments' => $commitmentRepository->findAll()]);
    }
}
