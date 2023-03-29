<?php

namespace App\Service;

use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService {
    private $session;
    private $produitRepository;


    public function __construct(
        RequestStack $requestStack,
        ProduitRepository $produitRepository)
    {
        $this->session=$requestStack;
        $this->produitRepository=$produitRepository;
    }

    public function add($id){
        $panier=$this->session->getSession()->get("panier",[]);

        // dans le cas ou la clé n'a jamais été alimenté
        // on a beoin de la créé avec une valeur
        if (empty($panier[$id])){
            // si le $panier[7] n'existe pas à la met à 0.
            $panier[$id]=0;
        }
        // dans le tableau de mon panier j'ai
        ///cart/add/7
        // $panier[7] je lui rajoute 1 dans sa veleur
        $panier[$id]++;

        // ici on modifie à chaque passage la variable panier
        // au niveau de la session
        // [7]=>1
        $this->session->getSession()->set("panier",$panier);

  


    }



 public function show() {

            //get pour recuperer la session
        // dd($session->getSession()->get("panier"));
        $panier=$this->session->getSession()->get("panier");

        // créé un panier contenant les infos sur le produits

        $panier_complet=[];

        // je boucle sur la cle et la valeur
        // du panier
        // clé de 7 sa valeur est la quantité
        $total=0;
        foreach ($panier as $key => $value  ){
            $produit_encours= $this->produitRepository->find($key);

            $panier_complet[]=[
                'produit'=> $produit_encours ,
                'quantite'=>$value,
                'total'=>($produit_encours->getPrix()*$value),
                ];
                // accumule la variable total avec chacun des prix
             
        }
        //dd($panier_complet);

        return $panier_complet; 

 }

    public function clear(){
         // remove pour vider la session
         $this->session->getSession()->remove("panier");


    }





}