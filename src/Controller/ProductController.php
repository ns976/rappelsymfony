<?php

namespace App\Controller;

use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductController extends AbstractController
{


    /**
     * @Route("/{slug}", name="product_category")
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
     * @Route("/{slug_category}/{slug_product}", name="showproduct")
     */
    public function showproduct(string $slug_category, string $slug_product, ProductRepository $ProductRepository): Response
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
    public function edit( int $id, EntityManagerInterface $em, ProductRepository $productRepository , Request $request ) : Response
    {
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
            return $this->redirectToRoute('showproduct', ['slug_category' => $product->getCategory()->getSlug() ,'slug_product'=> $product->getSlug()]);
        }



        return $this->render('product/edit.html.twig',['form'=>$formulaire->createView(),"product"=>$product] );
    }
}
