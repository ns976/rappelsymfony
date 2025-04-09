<?php

namespace App\Controller;


use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

class HelloWordController extends AbstractController
{


    public
    function __construct ()
    {
    }


    /**
     * @Route("/hello/{nom}", name="hello_word")
     */
    public function index(string $nom="",Calculator $calculator, LoggerInterface $logger,Slugify $slug): Response
    {
        dd($slug->slugify( "hello word "));
        $tva = $calculator->calcul( 100.00,$logger);

        return $this->render('hello_word/index.html.twig',['nom'=>$nom,'tva'=>$tva]);
    }
}
