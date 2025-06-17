<?php


    namespace App\Controller\Puchase;

    use App\cart\CartService;
    use App\Entity\Purchase;
    use App\Event\PurchaseSuccessEvent;
    use App\Repository\PurchaseRepository;
    use App\Stripe\StripeService;
    use Doctrine\ORM\EntityManagerInterface;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\EventDispatcher\EventDispatcherInterface;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class PurchasePaymentController extends AbstractController
    {

        /**
         * @Route("/purchase/pay/{id}", name="purchase_payment_form")
         * @IsGranted("ROLE_USER")
         * @return Response
         */
        public function purchasePaymentForm(int $id , PurchaseRepository $PurchaseRepository , StripeService $stripeService ) : Response{
            $purchase =  $PurchaseRepository->find( $id);
            if(!$purchase
               || ( ( $purchase && ( $purchase -> getUser() !== $this -> getUser() ) ) || ($purchase->getStatut() === Purchase::STATUT_PAYER ))){
                $this->addFlash( "warning" , "Erreur : aucune commande existante.");
                return  $this->redirectToRoute('cart_show');
            }
            $paymentIntent= $stripeService->getPaymentIntent( $purchase);

            return $this->render( "/purchases/payment.html.twig",["purchaseID"=>$id,
                                                                 "client_secret"=>$paymentIntent->client_secret]);
        }



        /**
         * @Route("/purchase/finish/{id}", name="purchase_payment_success")
         * @IsGranted("ROLE_USER")
         * @return Response
         */
        public function success(int $id , PurchaseRepository $PurchaseRepository,EntityManagerInterface $em,
                                CartService $CartService, EventDispatcherInterface $dispatcher ) : Response{

            /*  1/ je recuperate la commande de purchase */
            $purchase =  $PurchaseRepository->find( $id);

            //regarde si un panier existe ou si user du panier est le meme que l'utilisateur connecté ou statut n'est pas payé
            if(!$purchase
               || ( ( $purchase && ( $purchase -> getUser() !== $this -> getUser() ) ) || ($purchase->getStatut() === Purchase::STATUT_PAYER ))){
                $this->addFlash( "warning" , "Erreur : aucune commande existante.");
                return  $this->redirectToRoute('cart_show');
            }
            /* 2/ je la fait passer au statut paid */
            $purchase->setStatut( Purchase::STATUT_PAYER);
            $em->flush();

            /*  3/ je vide le panier*/
            $CartService->videPanier();

            //-- lancer un event envoie de mail de confirmation au client
               $purchaseEvent = new PurchaseSuccessEvent($purchase);
               $dispatcher->dispatch( $purchaseEvent, PurchaseSuccessEvent::EVENT_SUCCESS_EMAIL );


            /*4/ je redirige vers la liste des commandes*/
            $this->addFlash( "success" , "La commande  a bien été payé et confirmé.");
            return $this->redirectToRoute( "purchase_show");
        }

    }
