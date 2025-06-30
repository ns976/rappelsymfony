<?php

    namespace App\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class testError extends  AbstractController
    {

        /**
         * @Route("/testerror/{code_error}", name="test_error",priority="6")
         */
        public function testerror(int $code_error) : Response{
            if($code_error === 0){
                return $this->render( 'bundles/TwigBundle/Exception/error.html.twig');
            }
             return $this->render( 'bundles/TwigBundle/Exception/error'.$code_error.'.html.twig');
        }
    }