<?php

namespace App\Controller;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/*
Clé = Valeur
[clé] = valeur
[
    [clé]=>[valeur]
]
/cart/add/7
/cart/add/8
/cart/add/8
    [7]=>1
    [8]=>2
*/

#[Route('/cart')]
class CartController extends AbstractController
{
    // page d'ajout de produit
    #[Route('/add/{id}', name: 'app_cart_add')]
    public function index
    ($id,CartService $cartService):  Response
    {      
        $cartService->add($id);
        // redirection vers la page des produits
         return $this->redirectToRoute("app_cart_show",[], Response::HTTP_SEE_OTHER); 

    }

    // page pour visualiser notre panier
    #[Route('/show', name: 'app_cart_show')]
    public function show(CartService $cartService): Response
    {
        
        return $this->render('cart/index.html.twig', [
            'panier' => $cartService->show(),
            'totalcomplet' => $cartService->getTotalAll(),
            
        ]);
    }

    // page pour vider notre panier
    #[Route('/clear', name: 'app_cart_clear')]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();
        
        // redirection vers la page des produits
        return $this->redirectToRoute("app_produit_index",[], Response::HTTP_SEE_OTHER);

    }
    
    #[Route('/remove/{id}', name: 'app_cart_remove')]
    public function remove_all(CartService $cartService, $id): Response
    {
        $cartService->remove_all($id);
    
        
        return $this->redirectToRoute("app_cart_show",[], Response::HTTP_SEE_OTHER);

    }
    
    #[Route('/removequantite/{id}', name: 'app_cart_removequantite')]
    public function removequantite($id,CartService $cartService): Response
    {
        $cartService->remove($id);
        
        // redirection vers la page des produits
        return $this->redirectToRoute("app_cart_show",[], Response::HTTP_SEE_OTHER);

    }
}