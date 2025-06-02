<?php
namespace App\Controller;

    use App\Entity\Product;
    use App\Repository\ProductRepository;
    use Cocur\Slugify\Slugify;
    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class HomeController extends AbstractController
    {


        /**
         * @Route("/",name="homepage",priority="3")
         */
        public
        function homepage (ProductRepository $productRepository) : Response
        {
            $produitPhares = $productRepository->findBy( [], ['id' => 'DESC'], 3);
            return $this -> render( 'home.html.twig',['produitPhares'=>$produitPhares]);
        }
    }
