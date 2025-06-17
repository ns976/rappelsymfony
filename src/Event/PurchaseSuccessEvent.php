<?php

    namespace App\Event;

    use App\Entity\Purchase;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpKernel\KernelEvents;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Contracts\EventDispatcher\Event;

    class PurchaseSuccessEvent extends Event
    {
        const EVENT_SUCCESS_EMAIL = 'purchase_success';
        private  Purchase $purchase;

       public function __construct (Purchase $purchase) {
              $this->purchase = $purchase;

          }



         public function getpurchase(){
                return $this->purchase;
        }


    }