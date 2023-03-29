<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class CommandeController extends AbstractController
{
    #[Route('/profile/commande', name: 'app_commande')]
    public function index(
        CommandeRepository $commandeRepository,
        RequestStack $session,
        ProduitRepository $produitRepository


    ): Response
    {
        // stocké le user
        // this->getUser()
        // stocké le produit
        // via le param converter
        // et la quantité via la session
 
         // stocké le user
        // this->getUser()

        // recuperation du panier
        $panier=$session->getSession()->get("panier");

        // boucler sur chaque ligne du panier
        foreach ($panier as $key => $value  ){ 
            
            $commande = new Commande();
            $commande->setUsers($this->getUser());
            $commande->setProduits($produitRepository->find($key));
            $commande->setQuantite($value);
            $commandeRepository->save($commande, true);

        }
  
        dd("test");
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}