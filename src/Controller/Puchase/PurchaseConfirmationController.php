<?php

namespace App\Controller\Puchase;

use App\cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseConfirmationController extends AbstractController
{
    protected CartService  $CartService;
    protected EntityManagerInterface $em;

    public function __construct(CartService $CartService,EntityManagerInterface $EntityManagerInterface)
    {
        $this->CartService = $CartService;
        $this->em = $EntityManagerInterface;

    }
    /**
     * @Route("/purchase/confirmation", name="confirmPurchase")
    */
    public function confirmPurchase(Request $request): Response
    {

        //--Verifie si le panier est vide
        if ( $this->CartService->CartIsEmpty() ) {
            $this -> addFlash( "warning" , "Le panier est vide" );
            return $this -> redirectToRoute( 'cart_show' );
        }


        $Purchase = new Purchase();
        /**@var $user User*/
        $User = $this->getUser();
        $form = $this->createForm( CartConfirmationType::class,$Purchase);
        $form->handleRequest($request);



        if($form->isSubmitted() && $form->isvalid()){
            $Purchase->setUser( $User);

            foreach ($this->CartService->getCart() as $idproduct=>$quantite){
                $purchaseItem = new purchaseItem();
                $purchaseItem->setPurchase( $Purchase)
                             ->setProduct( $this->CartService->getProduct( $idproduct))
                             ->setQuantite( $quantite)
                             ->setProductName(  $this->CartService->getProduct( $idproduct)->getName())
                             ->setProductPrice( $this->CartService->getProduct( $idproduct)->getPrice())
                             ->setTotal( $this->CartService->totalProduct($idproduct));
                $this->em->persist( $purchaseItem);
                $Purchase ->addPurchaseItem( $purchaseItem);
            }

            $this->em->persist( $Purchase);

            $this->em->flush();
            $this->addFlash( "success" , "Votre commande a ete prise en compte");

            $this->CartService->videPanier();
            return $this->redirectToRoute( 'purchase_payment_form',["id"=>$Purchase->getid()]);
        }

        return $this->render('purchases/confirmation.html.twig',['form'=>$form->createView()] );
    }



}
