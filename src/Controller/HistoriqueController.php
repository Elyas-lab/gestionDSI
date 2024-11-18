<?php
namespace App\Controller;

use App\Repository\HistoriqueRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HistoriqueController extends AbstractController
{
    #[Route('/historique', name: 'app_historique_index')]
    public function index(
        Request $request, 
        HistoriqueRepository $historiqueRepository,
        PaginatorInterface $paginator
    ): Response {
        // Préparer les critères de recherche
        $criteria = [
            'type' => $request->query->get('type'),
            'dateDebut' => $request->query->get('dateDebut') ? 
                new \DateTime($request->query->get('dateDebut')) : null,
            'dateFin' => $request->query->get('dateFin') ? 
                new \DateTime($request->query->get('dateFin')) : null,
        ];

        // Récupérer la requête de base
        $query = $historiqueRepository->createHistoriqueQuery($criteria);

        // Paginer les résultats
        $historique = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), 
            15 // Nombre d'éléments par page
        );

        // Récupérer les types uniques pour le filtre
        $types = $historiqueRepository->findUniqueTypes();

        return $this->render('historique/index.html.twig', [
            'historique' => $historique,
            'types' => $types
        ]);
    }
}