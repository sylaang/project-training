<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/produit')]
class AdminProduitController extends AbstractController
{
    #[Route('/', name: 'app_admin_produit_index', methods: ['GET'])]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('admin_produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_produit_new', methods: ['GET', 'POST'])]
    public function new(FileUploader $fileUploader, Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageproduit=$form->get('image')->getData();
            
            if ($imageproduit) {
                $imageproduit_nom = $fileUploader->upload($imageproduit);
                $produit->setImagename($imageproduit_nom);
            }

            $produitRepository->save($produit, true);
            $produitRepository->save($produit, true);

            return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_produit_show', methods: ['GET'])]
    public function show(Produit $produit): Response
    {
        return $this->render('admin_produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, ProduitRepository $produitRepository, ): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, ProduitRepository $produitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produit->getId(), $request->request->get('_token'))) {
            $produitRepository->remove($produit, true);
        }

        return $this->redirectToRoute('app_admin_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}