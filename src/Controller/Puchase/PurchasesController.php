<?php

namespace App\Controller\Puchase;

use App\Entity\User;
use App\Repository\PurchaseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class PurchasesController extends AbstractController
{
    protected PurchaseRepository $purchaseRepository;


    public function __construct (PurchaseRepository $purchaseRepository) {
        $this->purchaseRepository = $purchaseRepository;

    }

    /**
     * @Route("/purchases/show", name="purchase_show")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à cette page.")
     */
    public function show(): Response
    {
        /**@var $user User*/
        $user = $this->getUser();
        $purchaseList  = $user->getPurchases();
        return $this->render('purchases/show.html.twig', compact( 'purchaseList' ) );
    }
}
