<?php

namespace App\EventSubscriber;

use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class ProductViewSubscriber implements EventSubscriberInterface
{
    public $logger;
    public function __construct(LoggerInterface $logger){
        $this->logger = $logger;
    }


    public  function sendmailAdministrateur(ProductViewEvent $event){
            $product = $event->getProduct();
            $this->logger->info( "Un nouvelle utilisateur a regarde le produit ".$product->getname());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductViewEvent::EVENT_PRODUCT_VIEW => 'sendmailAdministrateur',
        ];
    }
}
