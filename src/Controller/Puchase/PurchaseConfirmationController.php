<?php

namespace App\Controller\Puchase;

use App\cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
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
        $Purchase = new Purchase();

        $User = $this->getUser();
        $form = $this->createForm( CartConfirmationType::class,$Purchase);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isvalid()){
            $Purchase->setUser( $User)
                     ->setPuchaseAt(new \DateTimeImmutable())
                     ->setTotal( $this->CartService->totalCart());

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
            $this->addFlash( "success" , "Votre commande a &eacute;t&eacute; prise en compte");
            return $this->redirectToRoute( 'homepage');
        }

        return $this->render('purchases/confirmation.html.twig',['form'=>$form->createView()] );
    }
}
