<?php

namespace App\Controller;

use App\Entity\Championship;
use App\Entity\Licence;
use App\Entity\OrganizationTeam;
use App\Repository\CategoryRepository;
use App\Repository\ChampionshipRepository;
use App\Repository\ClubRepository;
use App\Repository\DocumentRepository;
use App\Repository\LicenceRepository;
use App\Repository\OrganizationTeamRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/** Pages publiques (ouverte à tous) */
class MainController extends AbstractController
{

    /**Page d'accueil */
    #[Route('/', name: 'app_main')]
    public function index(ChampionshipRepository $championshipRepository, ClubRepository $clubRepository, CategoryRepository $categoryRepository, OrganizationTeamRepository $organizationTeamRepository, LicenceRepository $licenceRepository,SeasonRepository $seasonRepository ): Response
    {

        // La saison en cours   
        $currentSeason = $seasonRepository->findOneBy(['isCurrentSeason' => true]);

        // affiché dans le composant events (championnat)
        $listeDesChampionnats = $championshipRepository->findBy(['season' => $currentSeason]);
        // sera affiché dans le composant "equipe"
        $listeDirigeants = $organizationTeamRepository->findAll();

        // DoiT etre implémenter en dql pour une meilleure performance
        $nombreClubs = count($clubRepository->findAll());
        $nombreChampionnats = count($listeDesChampionnats);
        $nombreCategories = count($categoryRepository->findAll());
        $nombreLicencies = count($licenceRepository->findBy(['season' => $currentSeason]));
        //******************************************************** */

        return $this->render('main/index.html.twig', [
            'listeDesChampionnats' => $listeDesChampionnats,
            'listeDirigeants' => $listeDirigeants,
            'nombreClubs' => $nombreClubs,
            'nombreChampionnats' => $nombreChampionnats,
            'nombreCategories' => $nombreCategories,
            'nombreLicencies' => $nombreLicencies,
        ]);
    }

    /**Page Clubs */
    #[Route('/clubs', name: 'app_clubs')]
    public function clubs(ClubRepository $clubRepository): Response
    {

        $clubs = $clubRepository->findBy(['archived' => false], ['name' => 'ASC']);

        return $this->render('main/clubs.html.twig', [
            'clubs' => $clubs,
        ]);
    }

    /*********** Pages sous compétition ************/

    /** Règlement et documents */
    #[Route('/competition/reglement', name: 'app_competition_reglement')]
    public function competitionReglement(DocumentRepository $documentRepository): Response
    {
     
        $documentsToDsiplay = $documentRepository->findBy(['published' => true], ['apparitionOrder' => 'ASC']);


        return $this->render('main/reglement.html.twig', [
          'documentsToDsiplay' => $documentsToDsiplay
        ]);
    }


    /** Dossards */
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

    /** Dossards -> impression*/
    #[Route('/competition/dossards/imprimer/{id}', name: 'app_competition_imprimer_dossard')]
    public function imprimerDossards(Licence $licence): Response
    {

        return $this->render('main/impressionDuDossard.html.twig', [
            'licence' => $licence,
        ]);
    }


    /** Dossards -> impression*/
    #[Route('/tutos/videos', name: 'app_tuto_video')]
    public function tutosVideos(): Response
    {

        return $this->render('main/tutos_videos.html.twig', [
        ]);
    }


}
