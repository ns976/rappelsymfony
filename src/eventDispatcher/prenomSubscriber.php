<?php

    namespace App\eventDispatcher;

    use Symfony\Component\EventDispatcher\EventDispatcher;
    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Symfony\Component\HttpKernel\Event\RequestEvent;
    use Symfony\Component\HttpKernel\KernelEvents;
    use Symfony\Contracts\EventDispatcher\Event;

    class prenomSubscriber implements  EventSubscriberInterface
    {

        public function addPrenomToAttribute( requestEvent $event ){
            $event->getRequest()->attributes->set('prenom', 'Jean');
        }

        public function test1(){
            dump('test1');
        }
        public function test2(){
            dump('test2');
        }

        public static
        function getSubscribedEvents ()
        {
         return   [  KernelEvents::REQUEST =>['addPrenomToAttribute'],
                     KernelEvents::CONTROLLER =>'test1',
                     KernelEvents::CONTROLLER_ARGUMENTS =>'test2'];
        }
    }