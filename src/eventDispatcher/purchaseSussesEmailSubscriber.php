<?php

    namespace App\eventDispatcher;

    use App\Entity\User;
    use App\Event\PurchaseSuccessEvent;
    use App\Entity\Purchase;
    use Symfony\Bridge\Twig\Mime\TemplatedEmail;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\Mailer\MailerInterface;
    use Symfony\Component\Mime\Address;

    class purchaseSussesEmailSubscriber implements EventSubscriberInterface
    {
        private $mail;

        public function __construct (MailerInterface $mail ) {
            $this->mail = $mail;

        }

        /**
         * Envoie un email de confirmation de commande à l'utilisateur
         * @param PurchaseSuccessEvent $event
         */

        public function sendmailconfirmation( PurchaseSuccessEvent $event){
            $Purchase = $event->getPurchase();
            /**@var  User  $User*/
            $User     = $Purchase->getUser();
            //--Version email avec template
            $email = (new TemplatedEmail()) -> to(new Address( $User->getEmail(), $User->getFullName()))
                ->from( 'contact@symshop.com' )
                ->subject( 'Votre commande  Ecommerce  n° '.$Purchase->getId().'à été expédié' )
                ->htmlTemplate( "email/purchase_send.html.twig")
                ->context( ['purchase'=>$Purchase,'user'=>$User]);
            $this->mail->send( $email);
        }


        public static
        function getSubscribedEvents ()
        {

            return [
                PurchaseSuccessEvent::EVENT_SUCCESS_EMAIL => 'sendmailconfirmation'
            ];
        }
    }