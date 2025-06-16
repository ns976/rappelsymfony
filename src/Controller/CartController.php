<?php

namespace App\Controller;

use App\cart\CartService;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
protected  $ProductRepository;
protected  $cartService;

    /**
     * @param $ProductRepository
     */
    public
    function __construct ( ProductRepository $ProductRepository ,CartService  $CartService)
    {
        $this -> ProductRepository = $ProductRepository;
        $this -> cartService = $CartService;
    }


    /**
     * @Route("/cart/add/{idproduct<\d+>}", name="cart_add")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à cette page.")
     */

    public function add(int $idproduct ): Response
    {
        $product = $this -> ProductRepository->find( $idproduct);

        if(!$product) {
            throw $this->createNotFoundException("Le produit n'existe pas");
        }
        $this->cartService->addProduct( $idproduct );

        $this->addflash( 'success', "Le produit ".$product->getName()." a été ajouté au panier" );

        return $this->redirectToRoute('showproduct',['slug_product' => $product->getSlug()]);
    }

   /**
     * @Route("/cart/show", name="cart_show")
     */
    public function showcart(): Response
    {

        $cart     =   $this -> cartService ->getCart();
        $totalCart =   $this -> cartService ->totalCart();

        return $this->render( 'cart/show.html.twig', ['cart'=>$cart,'totalCart'=>$totalCart]);
    }

    /**
     * @Route("/cart/decremente/{idproduct<\d+>}", name="cart_decremente")
     */
    public function decremente(int $idproduct): Response
    {

        $cart =    $this -> cartService ->getCart();
        if(array_key_exists( $idproduct , $cart)){
            $this->cartService->removeProduct($cart,$idproduct);
        }else{
            $this->addflash( 'error', "Le produit n'est pas dans le panier" );
        }

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/cart/incremente/{idproduct<\d+>}", name="cart_incremente")
     */
    public function incremente(int $idproduct)
    {
         $cart = $this->cartService->getCart();
        if(array_key_exists( $idproduct , $cart)){
           $this->cartService->addProduct($idproduct);
            $this->addflash( 'success', "La quantité du produit a été ajouté" );
        }else{
            $this->addflash( 'error', "Le produit n'est pas dans le panier" );
        }
        return $this->redirectToRoute('cart_show');
    }
    /**
     * Suprrime le produit du panier
     * @Route("/cart/delete/{idproduct<\d+>}", name="cart_delete")
     */
    public function delete(int $idproduct): Response
    {

        $cart = $this->cartService->getCart();
        if(array_key_exists( $idproduct , $cart)){
            $this -> cartService->remove(  $cart ,$idproduct);
            $this->addflash( 'success', "Le produit a été supprimé du panier" );
        }

        return $this->redirectToRoute('cart_show');
    }
    /**
     * Supprime complétement tout le panier
     * @Route("/cart/deleteall", name="cart_delete_all")
     */

    public function cartDeleteAll():Response
    {
        $this->cartService->videPanier();
        return $this->redirectToRoute('cart_show');
    }
}
