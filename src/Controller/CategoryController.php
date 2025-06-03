<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Repository\CategoryRepository;

use App\Security\Voter\CategoryVoter;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    protected $slug;
    protected $categoryRepository;
    protected $em;

    public function __construct(SluggerInterface $slug,EntityManagerInterface $em,CategoryRepository $categoryRepository)
    {
        $this->slug = $slug;
        $this->em = $em;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/admin/category/create", name="category_create")
     * @IsGranted("ROLE_ADMIN", message="Vous devez être administrateur pour accéder à cette page.")
     */
    public function create(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm( CategoryType::class);
        $form->handleRequest( $request );
        if($form->isSubmitted() && $form->isValid()){
            $categorie = $form->getData();
            $categorie->setSlug( $this->slug ->slug($categorie->getName()) );
            $categorie->setOrwner($user);
            $this->em->persist( $categorie);
            $this->em->flush();
            $this->addFlash( "success" , "La catégorie a été créée avec succès" );
        }

        return $this->render('category/create.html.twig',['form'=>$form->createView()] );
    }


    /**
     *
     * @Route("/admin/category/{id}/edit", name="category_edit")
     */
    public function edit(int $id,Request $request,CategoryRepository $categoryRepository): Response
    {

        $categorie = $categoryRepository->find($id);
        if(!$categorie){
            $this->addFlash("error" ,"La catégorie n'existe pas");
            return $this->redirectToRoute('homepage');
        }

        $this->denyAccessUnlessGranted( CategoryVoter::EDIT, $categorie,"Vous n'avez pas le droit de modifier cette catégorie" );




        $form = $this->createForm( CategoryType::class,$categorie);
        $form->handleRequest( $request );
        if($form->isSubmitted() && $form->isValid()){
            $categorie->setSlug( $this->slug->slug($categorie->getName()) );
            $this->em->flush();
            $this->addFlash( 'success' , 'La catégorie a été modifiée avec succès');
            return $this->redirectToRoute('product_category',[ 'slug' => $categorie->getSlug()  ]);
        }
        return $this->render('category/edit.html.twig' ,['categorie'=>$categorie,'form'=>$form->createView()]);
    }
}
