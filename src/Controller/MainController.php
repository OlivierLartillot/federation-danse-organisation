<?php

namespace App\Controller;

use App\Entity\Championship;
use App\Entity\OrganizationTeam;
use App\Repository\CategoryRepository;
use App\Repository\ChampionshipRepository;
use App\Repository\ClubRepository;
use App\Repository\OrganizationTeamRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ChampionshipRepository $championshipRepository, ClubRepository $clubRepository, CategoryRepository $categoryRepository, OrganizationTeamRepository $organizationTeamRepository ): Response
    {

        $listeDesChampionnats = $championshipRepository->findAll();
        // sera affiché dans le composant "equipe"
        $listeDirigeants = $organizationTeamRepository->findAll();

        // DOiT etre implémenter en dql pour une meilleure performance
        $nombreClubs = count($clubRepository->findAll());
        $nombreChampionnats = count($listeDesChampionnats);
        $nombreCategories = count($categoryRepository->findAll());
        //******************************************************** */

        return $this->render('main/index.html.twig', [
            'listeDesChampionnats' => $listeDesChampionnats,
            'listeDirigeants' => $listeDirigeants,
            'nombreClubs' => $nombreClubs,
            'nombreChampionnats' => $nombreChampionnats,
            'nombreCategories' => $nombreCategories
        ]);
    }
    #[Route('/clubs', name: 'app_clubs')]
    public function clubs(ClubRepository $clubRepository): Response
    {

        $clubs = $clubRepository->findBy([], ['name' => 'ASC']);

        return $this->render('main/clubs.html.twig', [
            'clubs' => $clubs,
        ]);
    }
}
