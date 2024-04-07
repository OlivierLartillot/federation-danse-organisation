<?php

namespace App\Controller\Admin;

use App\Entity\Championship;
use App\Form\ChampionshipInscriptionsType;
use App\Form\ChampionshipType;
use App\Repository\ChampionshipRepository;
use App\Repository\ClubRepository;
use App\Repository\LicenceRepository;
use App\Repository\SeasonRepository;
use App\Service\SwitchCurrent;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/championnats')]
class ChampionshipController extends AbstractController
{
    #[Route('/', name: 'app_admin_championship_index', methods: ['GET'])]
    public function index(ChampionshipRepository $championshipRepository): Response
    {
        return $this->render('admin/championship/index.html.twig', [
            'championships' => $championshipRepository->allChampionshipsByCurrentSeason(),
        ]);
    }

    #[Route('/new', name: 'app_admin_championship_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SwitchCurrent $switchCurrentChampionship): Response
    {
        $championship = new Championship();
        $form = $this->createForm(ChampionshipType::class, $championship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Il ne peut pas y avoir 2 championnats Courant en même temps !!!
            // si le championnat du formulaire devient le courant il faut regarder tous les autres et passer à false les autres
            // ATENTION: Si on le met a false, il peut ne plus y avoir de championnat courant
            $switchCurrentChampionship->switchCurrentChampionship($championship);

            $entityManager->persist($championship);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_championship_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/championship/new.html.twig', [
            'championship' => $championship,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_championship_show', methods: ['GET'])]
    public function show(Championship $championship): Response
    {
        return $this->render('admin/championship/show.html.twig', [
            'championship' => $championship,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_championship_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Championship $championship, EntityManagerInterface $entityManager, SwitchCurrent $switchCurrentChampionship): Response
    {
        $form = $this->createForm(ChampionshipType::class, $championship);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Il ne peut pas y avoir 2 championnats Courant en même temps !!!
            // si le championnat du formulaire devient le courant il faut regarder tous les autres et passer à false les autres
            // ATENTION: Si on le met a false, il peut ne plus y avoir de championnat courant
            $switchCurrentChampionship->switchCurrentChampionship($championship);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_championship_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/championship/edit.html.twig', [
            'championship' => $championship,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_championship_delete', methods: ['POST'])]
    public function delete(Request $request, Championship $championship, EntityManagerInterface $entityManager): Response
    {

        // si il y a des inscriptions -> tu ne peux pas supprimer le chanmpionnat
        // retourne dans la page du championnat show
        if (count($championship->getLicences())) {
           
            $this->addFlash(
                'danger',
                'Vous ne pouvez pas supprimer ce championnat car il y a des licences inscrites. Si vous êtes certains de vouloir supprimer ce championnat, veuillez supprimer les inscriptions associées avant de recommencer.'
            );

            return $this->redirectToRoute('app_admin_championship_show', ['id' => $championship->getId()], Response::HTTP_SEE_OTHER);


        }

        if ($this->isCsrfTokenValid('delete'.$championship->getId(), $request->request->get('_token'))) {
            $entityManager->remove($championship);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_championship_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/inscriptions/clubs', name: 'app_admin_championship_index_inscriptions', methods: ['GET'])]
    public function indexInscriptions(ChampionshipRepository $championshipRepository): Response
    {

        return $this->render('admin/championship/index_inscriptions.html.twig', [
            'championships' => $championshipRepository->allChampionshipsByCurrentSeason(),
        ]);
    }

    #[Route('/inscriptions/clubs/{id}', name: 'app_admin_championship_show_inscriptions', methods: ['GET'])]
    public function showInscriptions(Championship $championship, ClubRepository $clubRepository, LicenceRepository $licenceRepository, SeasonRepository $seasonRepository): Response
    {

        $currentSeason = $seasonRepository->findOneBy(['isCurrentSeason' => true]);



        if ($this->isGranted('ROLE_SECRETAIRE')) {
            $licences =  $licenceRepository->findBy(['season' => $currentSeason]) ? $licenceRepository->findBy(['season' => $currentSeason])  : [] ;
        }
        else {
            // si je suis un club je vois mes inscrits
            $myClub = $clubRepository->findOneBy(['owner' => $this->getUser()]);
            $licences =  $licenceRepository->findBy(['club' => $myClub, 'season' => $currentSeason]) ? $licenceRepository->findBy(['club' => $myClub, 'season' => $currentSeason], ['category' => 'ASC']) : [] ;
        }

     
        $myChampionshipLicences = [];
        foreach ($championship->getLicences() as $licence) {
            // si cette licence est dans mon tableau de mon club, je la conserve
            if (in_array($licence, $licences)){
                $myChampionshipLicences[] = $licence;
            }
        }

        //dd($myChampionshipLicences);
        return $this->render('admin/championship/show_inscriptions.html.twig', [
            'championship' => $championship,
            'myChampionshipLicences' => $myChampionshipLicences
        ]);
    }

    #[Route('/inscriptions/{id}/edit', name: 'app_admin_championship_edit_inscriptions', methods: ['GET', 'POST'])]
    public function inscriptions(Request $request, Championship $championship, EntityManagerInterface $entityManager, SeasonRepository $seasonRepository, ClubRepository $clubRepository, LicenceRepository $licenceRepository): Response
    {

        $currentSeason = $seasonRepository->findOneBy(['isCurrentSeason' => true]);
        // si tu n est pas l admin et que la date limite est dépassée tu te fais virer
        $dateTime = new DateTime('now');
       
        $dateLimite = $championship->getChampionshipInscriptionsLimitDate();

        if($dateTime->diff($dateLimite)->d > 0 && $dateTime->diff($dateLimite)->invert > 0 ) 
        {
            if (!$this->isGranted('ROLE_SUPERMAN')) { return throw $this->createAccessDeniedException();}
        }

        $user= $this->getUser();
        $myClub = $clubRepository->findOneBy(['owner' => $user]);
        $licences = $licenceRepository->findBy(['season' => $currentSeason]) ? $licenceRepository->findBy(['season' => $currentSeason]): [] ;

        $form = $this->createForm(ChampionshipInscriptionsType::class, $championship);
        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {

            //est ce que je suis un club ? 
            
            // si je suis un club, récupérer toutes les licences qui m'appartiennent NON PARCEQUE C EST FILTRE PAR LE FORM
            // DONC ON GARDE FIND ALL
            //$licences = $licenceRepository->findBy(['club' => $myClub]) ? $licenceRepository->findBy(['club' => $myClub]) : [];
            // tableau des licences du de ce championnat
            $championshipLicences = [];
            foreach ($championship->getLicences() as $licence) {
                $championshipLicences[] = $licence;
            }

            // comparer si ces licences sont dans le tableau des inscriptions
            $checkboxPostArray = (isset($_POST['championship_inscriptions']['licences'])) ? $_POST['championship_inscriptions']['licences'] : [];

            // pour chaque licence on va voir si elle est dans le tableau des inscriptions
            // puis si elle est dans les checkbox soit on ajoute soit on retire soit on fait rien
            foreach ($licences as $licence) {
                if (in_array($licence, $championshipLicences)) {
                    // si cette licence est dans le tableau
                    if (!in_array($licence->getId(), $checkboxPostArray)) {
                       //dd('la licence est dans le championshiplicence mais pas  dans le tableau checkbox');
                       // du coup si c est pas coché il faut l'enlever !!
                       $championship->removeLicence($licence);
                    }
                } 
                else {
                    if (in_array($licence->getId(), $checkboxPostArray)) {
                            //dd('la licence ne st pas dans le championshiplicence mais a été coché dans le tableau checkbox');
                            // il faut l'ajouter
                           $championship->addLicence($licence);
                        }
                }
            }
             
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Vos changements ont été enregistrés.'
            );

            return $this->redirectToRoute('app_admin_championship_edit_inscriptions', ['id' => $championship->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/championship/inscriptions.html.twig', [
            'championship' => $championship,
            'form' => $form,
            'user' => $user,
            'myClub' => $myClub,
            /* 'licences' => $licences */
        ]);
    }


}
