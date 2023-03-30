<?php

namespace App\Controller;

use App\Entity\Commandes;
use App\Entity\Facture;
use App\Repository\CommandesRepository;
use App\Repository\FactureRepository;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use App\Repository\ProduitRepository; 
use App\Service\CartService;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{

    #[Route('/profile/commande/success', name: 'app_commande_sucess')]
    public function sucess(){
        'le paiement est OK ! ';
    } 


    #[Route('/profile/commande/cancel', name: 'app_commande_cancel')]
    public function cancel(){
        dd('le paiement est KO ! ');
    } 

    #[Route('/profile/commande', name: 'app_commande')]
    public function index(
     //   CommandeRepository $commandeRepository,
        RequestStack $session,
        ProduitRepository $produitRepository,

        CartService $cart,
        FactureRepository $factureRepository,

        CommandesRepository $commandesRepository


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
    /*    $panier=$session->getSession()->get("panier");
        // boucler sur chaque ligne du panier
        foreach ($panier as $key => $value  ){ 
            
            $commande = new Commande();
            $commande->setUsers($this->getUser());
            $commande->setProduits($produitRepository->find($key));
            $commande->setQuantite($value);
            $commandeRepository->save($commande, true);
        }*/


        //1. Payer sur STRIPE
        // communiquer avec stripe

        // on a le montant du panier
        $montant=$cart->getTotalAll()*100;


        // 1. On va stocké une ligne dans la table facture
        // on créé un objet facture issue de l'entité facture
        $facture=new Facture();
        // on va lui affecté un user en lui mettant l'user en cours
        $facture->setUsers($this->getUser());
        // on va lui affecté la propriété correspondant à la date en cours
        // avec un datatime
        $facture->setDatecrea(new DateTime());



        // on utilise le repo de la facture pour enregistrer
        // les repository des entity de servent qu'a lire (méthode find)
        // il y a une personnalisation du repo qui appelle l'entity manager
        // c'est la classe d'écriture de symfony
        $factureRepository->save($facture, true);




        // 2. on va stocké le panier dans la table commande
    /*    $panier=$session->getSession()->get("panier");
        // boucler sur chaque ligne du panier
        foreach ($panier as $key => $value  ){ 
            
            $commande = new Commande();
            $commande->setUsers($this->getUser());
            $commande->setProduits($produitRepository->find($key));
            $commande->setQuantite($value);
            $commandeRepository->save($commande, true);
        }*/
        $panier=$session->getSession()->get("panier");

        foreach($panier as $key => $value){

            // création d'un objet commande
            $commandes=new Commandes();
            // affectation de la propriété quantité issue du tableau panier
            $commandes->setQuantite($value);
            // affectation de la propriété produit
            // grace au repo du produit
            $commandes->setProduit($produitRepository->find($key));
            // affectation de la propriété facture issue du 
            // de la facture créé au dessus
            $commandes->setFactures($facture);
            $commandesRepository->save($commandes,true);
        }



        

        // clé secrete pour que stripe me reconnaisse
        $stripeSecretKey="sk_test_51MrG5fLM09gwrAQw61IK7poeloq9ZceWAY2MkKANR3o1CiSLa1MbLxeCZI80IAzxyEhUxeKqSx2Az3sQ6qL4uvd600FFknN4Vp";
        Stripe::setApiKey($stripeSecretKey);
  
        // on définit le protocol de connexion http ou https
        // avec les variable global PHP de sorte de pouvoir gérer
        // tout les environnements possible
        $protocol="http://";
        // on définit le nom du serveur de connexion 
        // avec les variable global PHP de sorte de pouvoir gérer
        // tout les environnements possible
        $serveur=$_SERVER['SERVER_NAME'];
        $YOUR_DOMAIN=$protocol.$serveur;

        // on lance la communication avec stripe



        $checkout_session = \Stripe\Checkout\Session::create([
            'line_items' => [[
              # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
              'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $montant,
                'product_data' => [
                  'name' => 'Paiement de votre panier'
                ],
              ],
              'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/profile/commande/success',
            'cancel_url' => $YOUR_DOMAIN . '/profile/commande/cancel',

        ]);
 
          
        //2. Savegarde en B.D.

        //3. Supprimer la session
  
        
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
            'id_session'=>$checkout_session->id
        ]);
    }
}