<?php

namespace App\Controller;


use App\Taxes\Detector;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class HelloWordController extends AbstractController
{


    /**
     * @Route("/hello/{nom}", name="hello_word")
     */
    public function index(string $nom="",Calculator $calculator, LoggerInterface $logger,Slugify $slug,Detector $detector): Response
    {
            dump($slug->slugify( "hello word "));
            $tva = $calculator->calcul( 100.00,$logger);
            dump($detector->detect( 100));
            dump($detector->detect( 110));
       return $this->render('hello_word/index.html.twig',['nom'=>$nom,'tva'=>$tva]);
    }

    /**
     * @Route("/testtwig/{prenom}", name="test_twig")
     */
    public function testtwig(string $prenom) : Response{

        $formateur = ['prenom'=>'Jean','nom'=>'Jacques','age'=>12];
        $formateur_deux = ['prenom'=>'John','nom'=>'Doe','age'=>12];

        return $this->render('hello_word/test.html.twig',["prenom"=>$prenom,"formateur1"=>$formateur,"formateur2"=>$formateur_deux]);
    }
}
