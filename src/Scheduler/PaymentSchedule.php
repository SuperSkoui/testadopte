<?php
namespace App\Scheduler;


use App\Message\CheckPaymentDue;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

class PaymentSchedule implements ScheduleProviderInterface {
    public function getSchedule(): Schedule {
        return (new Schedule())
            ->add(RecurringMessage::every('1 day', new CheckPaymentDue()));
    }
}