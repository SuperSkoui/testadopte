<?php

namespace App\MessageHandler;

use App\Entity\Payment;
use App\Message\CheckPaymentDue;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CheckPaymentDueHandler
{
    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $entityManager){

    }
    public function __invoke(CheckPaymentDue $message): void
    {
        $users=$this->userRepository->findAllDataForActiveCommitment();
        foreach ($users as $user){
            if($user->getLastPayment() !== null && (date($user->getLastPayment()->getDate().'+ '.$user->getCommitment()->getDuration().' Days'))< new \DateTimeImmutable()){
                /*
                 * TODO:call api payment
                 * */
                $user->setLastPayment(new \DateTimeImmutable());
                $payment=new Payment();
                $payment->setUser($user);
                $payment->setAmount($user->getCommitment()->getCost());
                $payment->setDate(new \DateTimeImmutable());
                $this->entityManager->persist($user);
                $this->entityManager->persist($payment);
                $this->entityManager->flush();

            }
        }
        // do something with your message
    }
}
