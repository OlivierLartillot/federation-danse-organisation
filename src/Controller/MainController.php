<?php

namespace App\Controller;

use App\Entity\Championship;
use App\Entity\Licence;
use App\Entity\OrganizationTeam;
use App\Repository\CategoryRepository;
use App\Repository\ChampionshipRepository;
use App\Repository\ClubRepository;
use App\Repository\LicenceRepository;
use App\Repository\OrganizationTeamRepository;
use App\Repository\SeasonRepository;
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

    #[Route('/competition/dossards', name: 'app_competition_dossards')]
    public function competitionDossards(LicenceRepository $licenceRepository, SeasonRepository $seasonRepository): Response
    {

        // rechercher la saison en cours
        $currentSeason = $seasonRepository->findOneBy(['isCurrentSeason' => true]);

        // récupérer les licences de la saison en cours et faire un group by categories

    
        /* $licences = $licenceRepository->findBy(['season' => $currentSeason], ['dossard' => 'ASC']); */
        $licences = $licenceRepository->findDossardGroupByCategories($currentSeason);

        return $this->render('main/dossards.html.twig', [
            'licences' => $licences,
        ]);
    }

    #[Route('/competition/imprimer/{id}', name: 'app_competition_imprimer_dossard')]
    public function imprimerDossards(Licence $licence): Response
    {

        return $this->render('main/impressionDuDossard.html.twig', [
            'licence' => $licence,
        ]);
    }


}
