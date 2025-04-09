<?php
namespace App\Controller;

    use App\Entity\Product;
    use Cocur\Slugify\Slugify;
    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\EntityManagerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class HomeController extends AbstractController
    {

        /**
         * @Route("/",name="homepage")
         */
        public
        function homepage () : Response
        {
            return $this -> render( 'home.html.twig');
        }
    }
