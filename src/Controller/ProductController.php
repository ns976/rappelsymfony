<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à cette page.")
 */
class ProductController extends AbstractController
{


    /**
     * @Route("/{slug}", name="product_category", priority="-1")
     */
    public function category(string $slug, CategoryRepository $categoryRepository): Response
    {
       $category =  $categoryRepository->findOneBy( ['slug' => $slug]);

       if( !$category ) {
           throw $this->createNotFoundException('Category not found');
        }

        return $this->render('product/category.html.twig', compact( 'category' ) );
    }




    /**
     * @Route("/show/{slug_product}", name="showproduct",priority="-1")
     */
    public function showproduct( string $slug_product, ProductRepository $ProductRepository): Response
    {
        $product =  $ProductRepository->findOneBy( ['slug' => $slug_product]);

        if( !$product ) {
            throw $this->createNotFoundException("le produit existe pas");
        }

        return $this->render('product/product.html.twig', compact( 'product' ) );
    }


    /**
     * @Route("/admin/product/create", name="product_create")
     */
    public function create( EntityManagerInterface $em,Request $request ) : Response
    {


        $formulaire = $this->createForm( ProductType::class);

        $formulaire->handleRequest( $request );
        if($formulaire->isSubmitted() && $formulaire->isValid()){
            $product = $formulaire->getData();
            $product->setSlug($product->getname());

            $em->persist($product);
            $em->flush();
            $this->addFlash('success','Produit crée avec succèss');
            return $this->redirectToRoute('product_category', ['slug' => $product->getCategory()->getSlug() ]);
        }



        return $this->render('product/create.html.twig',['form'=>$formulaire->createView()] );
    }


    /**
     * @Route("/admin/product/edit/{id}", name="product_edit")
     */
    public function edit( int $id, EntityManagerInterface $em, ProductRepository $productRepository , Request $request ,ValidatorInterface $validator) : Response
    {
//       $this->denyAccessUnlessGranted( "ROLE_ADMIN" ,null,"Vous n'avez pas accès necessaire" );

        $product = $productRepository->find( $id);
        if(!$product){
            $this->addFlash('warning','Le produit n\'existe pas ou plus');
            return $this->redirectToRoute('homepage' );
        }


        $formulaire = $this->createForm( ProductType::class,$product);
        $formulaire->handleRequest( $request );
        if($formulaire->isSubmitted() && $formulaire->isValid()){
            $product->setSlug($product->getname());
            $em->flush();
            $this->addFlash('success','Produit modifié  avec succèss');
            return $this->redirectToRoute('showproduct', ['slug_product'=> $product->getSlug()]);
        }



        return $this->render('product/edit.html.twig',['form'=>$formulaire->createView(),"product"=>$product] );
    }
}
