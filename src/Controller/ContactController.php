<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\MailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(MailService $mailService,Request $request): Response
    {


        $form=$this->createForm(ContactType::class);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $data= $form->getData();

            
            
            $email_form=$data['votre_email'];
            $message_form=$data['votre_message'];

            $mailService->sendEmail( 
                $email_form, 
                $data['votre_message']);


        return $this->renderForm('contact/traitement.html.twig', [
        ]);



    }
    return $this->renderForm('contact/index.html.twig', [
        'form' => $form,
    ]);
}
}