<?php

namespace App\Controller\Admin;

use App\Entity\Statut;
use App\Form\StatutType;
use App\Service\Gestion\StatutGestion;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/statut')]
final class StatutController extends AbstractController
{
    private StatutGestion $statutGestion;

    public function __construct(StatutGestion $statutGestion)
    {
        $this->statutGestion = $statutGestion;
    }

    #[Route('/', name: 'app_statut_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        // Paginer les résultats
        $statutsQuery = $this->statutGestion->getAvailableStatuts(); // Par exemple : Doctrine QueryBuilder ou tableau
        $pagination = $paginator->paginate(
            $statutsQuery,
            $request->query->getInt('page', 1), // Page actuelle
            10 // Nombre d'éléments par page
        );
    
        return $this->render('statut/index.html.twig', [
            'statuts' => $pagination->getItems(), // Les données paginées
            'pagination' => $pagination // Pour l'affichage de la pagination
        ]);
    }
    
    
    #[Route('/statut/data', name: 'app_statut_data', methods: ['GET'])]
    public function data(): JsonResponse
    {
        $statuts = $this->statutGestion->getAvailableStatuts();
    
        // Formater les données pour DataTables
        $data = [];
        foreach ($statuts as $statut) {
            $data[] = [
                'id' => $statut->getId(),
                'name' => $statut->getName(),
                'created_at' => $statut->getCreatedAt()->format('Y-m-d'),
                // Ajoutez d'autres champs nécessaires ici
            ];
        }
    
        return new JsonResponse(['data' => $data]);
    }
    
    #[Route('/new', name: 'app_statut_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $statut = new Statut();
        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->statutGestion->createStatut(
                $statut->getTitre(),
                $statut->getDescription()
            );

            return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statut/new.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_statut_show', methods: ['GET'])]
    public function show(Statut $statut): Response
    {

        if (!$statut) {
            throw $this->createNotFoundException('Statut introuvable.');
        }

        return $this->render('statut/show.html.twig', [
            'statut' => $statut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_statut_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        $statut = $this->statutGestion->getStatutById($id);

        if (!$statut) {
            throw $this->createNotFoundException('Statut introuvable.');
        }

        $form = $this->createForm(StatutType::class, $statut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->statutGestion->updateStatut(
                $statut,
                $statut->getTitre(),
                $statut->getDescription()
            );

            return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('statut/edit.html.twig', [
            'statut' => $statut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_statut_delete', methods: ['POST'])]
    public function delete(Request $request, int $id): Response
    {
        $statut = $this->statutGestion->getStatutById($id);

        if (!$statut) {
            throw $this->createNotFoundException('Statut introuvable.');
        }

        if ($this->isCsrfTokenValid('delete' . $statut->getId(), $request->request->get('_token'))) {
            $this->statutGestion->deleteStatut($statut);
        }

        return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/initialize', name: 'app_statut_initialize', methods: ['POST','GET'])]
    public function initializeStatuts(): Response
    {
        // Appelle la méthode du service pour initialiser les statuts par défaut
        $this->statutGestion->initializeDefaultStatuts();

        $this->addFlash('success', 'Les statuts par défaut ont été initialisés avec succès.');

        return $this->redirectToRoute('app_statut_index', [], Response::HTTP_SEE_OTHER);
    }

}
