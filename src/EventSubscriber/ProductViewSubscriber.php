<?php

namespace App\EventSubscriber;

use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;


class ProductViewSubscriber extends  AbstractController implements EventSubscriberInterface
{
    public $logger;
    public $mailer;
    public function __construct(LoggerInterface $logger , MailerInterface $mailer){
        $this->logger = $logger;
        $this->mailer = $mailer;
    }


    public  function sendmailAdministrateur(ProductViewEvent $event){
            $email = new Email();
              $email->to( 'test@adresse.com')
                    ->from(new Address( $this->getParameter('no_reply') , 'No Reply'))
                    ->subject('Un produit a été consulté')
                    ->text('Un produit a été consulté : ' . $event->getProduct()->getName());


             $this->mailer->send($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
          //  ProductViewEvent::EVENT_PRODUCT_VIEW => 'sendmailAdministrateur',
        ];
    }
}
