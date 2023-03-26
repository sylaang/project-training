<?php

namespace App\Controller;

use App\Form\TvaType;
use App\Service\TvaService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TvaController extends AbstractController
{
    #[Route('/tva', name: 'app_tva')]
    public function index(Request $request,TvaService $tvaService): Response
    {

        $form = $this->createForm(TvaType::class);
        // on prend l'objet form qui va lire la request
        $form->handleRequest($request);

        // test si l'envoie en post et est valide est bien envoyé
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$data` variable has also been updated

            // on stock dans une variable nommé data 
            // les données récupéré [tva=>12]
            $data = $form->getData();
            // cacul  de la TVA STOCKE DANS LA VAR TTC
            
            $data['tva']=$tvaService->calcul($data['prix']);
            
            //$data['ttc']=$data['prix']-$data['tva'];
            $data['ttc']=$tvaService->calcul_ht($data['prix']);
            
                    
            
            return $this->render('tva/calcul.html.twig', [
                'tva'=>$data
            ]);
        }

         return $this->renderForm('tva/index.html.twig', [
            'form'=>$form
        ]);
    }
}
