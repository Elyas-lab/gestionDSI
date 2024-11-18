<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\HistoriqueRepository;

class HistoriqueController extends AbstractController
{
    #[Route('/historique', name: 'app_historique')]
    public function index(): Response
    {
        return $this->render('historique/index.html.twig', [
            'controller_name' => 'HistoriqueController',
        ]);
    }

    /**
     * Action pour afficher l'historique d'un élément spécifique
     * Cette méthode est utilisée par le render(controller()) dans les templates
     */
    public function elementHistorique(
        string $type,
        int $id,
        int $limit,
        HistoriqueRepository $historiqueRepository
    ): Response {
        $historique = $historiqueRepository->findBy(
            ['typeElement' => $type, 'idElement' => $id],
            ['dateHistorique' => 'DESC'],
            $limit
        );

        return $this->render('historique/_element_historique.html.twig', [
            'historique' => $historique,
            'type' => $type,
            'id' => $id
        ]);
    }
}
