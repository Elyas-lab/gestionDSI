<?php

namespace App\Controller;

use App\Entity\StatutDemande;
use App\Form\StatutDemandeType;
use App\Repository\StatutDemandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/statut_demande')]
final class StatutDemandeController extends AbstractController
{
    #[Route(name: 'app_statut_demande_index', methods: ['GET'])]
    public function index(StatutDemandeRepository $statutDemandeRepository): Response
    {
        return $this->render('statut_demande/index.html.twig', [
            'statut_demandes' => $statutDemandeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_statut_demande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $statutDemande = new StatutDemande();
        $form = $this->createForm(StatutDemandeType::class, $statutDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($statutDemande);
            $entityManager->flush();

            return $this->redirectToRoute('app_statut_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statut_demande/new.html.twig', [
            'statut_demande' => $statutDemande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statut_demande_show', methods: ['GET'])]
    public function show(StatutDemande $statutDemande): Response
    {
        return $this->render('statut_demande/show.html.twig', [
            'statut_demande' => $statutDemande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_statut_demande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, StatutDemande $statutDemande, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatutDemandeType::class, $statutDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_statut_demande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statut_demande/edit.html.twig', [
            'statut_demande' => $statutDemande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statut_demande_delete', methods: ['POST'])]
    public function delete(Request $request, StatutDemande $statutDemande, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$statutDemande->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($statutDemande);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_statut_demande_index', [], Response::HTTP_SEE_OTHER);
    }
}
