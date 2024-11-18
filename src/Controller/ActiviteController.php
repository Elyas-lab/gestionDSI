<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use App\Service\HistoriqueService;
use App\DTO\TypeElement;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/activite')]
final class ActiviteController extends AbstractController
{
    private $historiqueService;

    public function __construct(HistoriqueService $historiqueService)
    {
        $this->historiqueService = $historiqueService;
    }

    #[Route(name: 'app_activite_index', methods: ['GET'])]
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_activite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($activite);
            $entityManager->flush();

            // Ajouter l'entrée dans l'historique
            $this->historiqueService->addHistorique(
                TypeElement::Activite,
                $activite->getId(),
                'Création d\'une nouvelle activité'
            );

            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_show', methods: ['GET'])]
    public function show(Activite $activite): Response
    {
        // Récupérer l'historique de l'activité
        $historique = $this->historiqueService->getHistorique(
            TypeElement::Activite->value,
            $activite->getId()
        );

        return $this->render('activite/show.html.twig', [
            'activite' => $activite,
            'historique' => $historique,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_activite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // Ajouter l'entrée dans l'historique
            $this->historiqueService->addHistorique(
                TypeElement::Activite,
                $activite->getId(),
                'Modification de l\'activité'
            );

            return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_activite_delete', methods: ['POST'])]
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->getPayload()->getString('_token'))) {
            // Sauvegarder l'ID avant la suppression
            $activiteId = $activite->getId();
            
            $entityManager->remove($activite);
            $entityManager->flush();

            // Ajouter l'entrée dans l'historique
            $this->historiqueService->addHistorique(
                TypeElement::Activite,
                $activiteId,
                'Suppression de l\'activité'
            );
        }

        return $this->redirectToRoute('app_activite_index', [], Response::HTTP_SEE_OTHER);
    }
}