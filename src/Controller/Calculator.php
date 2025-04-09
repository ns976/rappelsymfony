<?php

    namespace App\Controller;

    use Psr\Log\LoggerInterface;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class Calculator extends AbstractController
    {
    protected $tva;
    protected $logger;

        /**
         * @param $tva
         */
        public
        function __construct (float  $tva ,LoggerInterface $logger)
        {

            $this -> tva = $tva;

        }

        /**
         * @Route("/calcul", name="calcul")
         */
        public function calcul(float $prix, LoggerInterface  $logger): float
        {
            $logger->info( "calcul de la taxe en cours avec le prix $prix");
            return $prix*(20/100);
        }
    }
