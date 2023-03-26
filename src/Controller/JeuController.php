<?php

namespace App\Controller;

use App\Form\JeuType;
use App\Service\JeuService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JeuController extends AbstractController
{
    #[Route('/jeu', name: 'app_jeu')]
    public function index(Request $request, JeuService $jeuService): Response
    {
        $form=$this->createForm(JeuType::class);

        // on prend l'objet form qui va lire la request
        $form->handleRequest($request);

        // test si l'envoie en post et est valide est bien envoyé
        if ($form->isSubmitted() && $form->isValid()) {
 
            // creer une variable data qui est un tableau clé valeur
            // contenant les données envoyé en POST
            $data= $form->getData();
    
           
    
            // une variable aléatoire va être généré  et 
            // stocké dans le tableau data sur la clé alea
            $data['alea']=rand(1,100);
    
            $data['reponse']=$jeuService->deviner($data['nombre'], $data['alea']);


        $this->render('jeu/traitement.html.twig', [
                'mes_donnes'=>$data,
            ]);
        }
        return $this->renderForm('jeu/index.html.twig', [
            'form' => $form,
        ]);
    }
}