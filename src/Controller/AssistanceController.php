<?php

namespace App\Controller;

use App\Entity\Assistance;
use App\Form\AssistanceType;
use App\Repository\AssistanceRepository;
use App\Service\HistoriqueService;
use App\DTO\TypeElement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/assistance')]
final class AssistanceController extends AbstractController
{
    private $historiqueService;

    public function __construct(HistoriqueService $historiqueService)
    {
        $this->historiqueService = $historiqueService;
    }

    #[Route(name: 'app_assistance_index', methods: ['GET'])]
    public function index(AssistanceRepository $assistanceRepository): Response
    {
        return $this->render('assistance/index.html.twig', [
            'assistances' => $assistanceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_assistance_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $assistance = new Assistance();
        $form = $this->createForm(AssistanceType::class, $assistance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($assistance);
            $entityManager->flush();

            // Ajouter l'entrée dans l'historique
            $this->historiqueService->addHistorique(
                TypeElement::Assistance,
                $assistance->getId(),
                'Création d\'une nouvelle demande d\'assistance'
            );

            return $this->redirectToRoute('app_assistance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('assistance/new.html.twig', [
            'assistance' => $assistance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_assistance_show', methods: ['GET'])]
    public function show(Assistance $assistance): Response
    {
        // Récupérer l'historique de l'assistance
        $historique = $this->historiqueService->getHistorique(
            TypeElement::Assistance->value,
            $assistance->getId()
        );

        return $this->render('assistance/show.html.twig', [
            'assistance' => $assistance,
            'historique' => $historique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_assistance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Assistance $assistance, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AssistanceType::class, $assistance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Ajouter l'entrée dans l'historique
            $this->historiqueService->addHistorique(
                TypeElement::Assistance,
                $assistance->getId(),
                'Modification de la demande d\'assistance'
            );

            return $this->redirectToRoute('app_assistance_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('assistance/edit.html.twig', [
            'assistance' => $assistance,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_assistance_delete', methods: ['POST'])]
    public function delete(Request $request, Assistance $assistance, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$assistance->getId(), $request->getPayload()->getString('_token'))) {
            // Sauvegarder l'ID avant la suppression
            $assistanceId = $assistance->getId();
            
            $entityManager->remove($assistance);
            $entityManager->flush();

            // Ajouter l'entrée dans l'historique
            $this->historiqueService->addHistorique(
                TypeElement::Assistance,
                $assistanceId,
                'Suppression de la demande d\'assistance'
            );
        }

        return $this->redirectToRoute('app_assistance_index', [], Response::HTTP_SEE_OTHER);
    }
}