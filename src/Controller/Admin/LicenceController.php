<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Entity\Licence;
use App\Form\LicenceType;
use App\Repository\ClubRepository;
use App\Repository\LicenceRepository;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/licence')]
class LicenceController extends AbstractController
{
    #[Route('', name: 'app_admin_licence_index', methods: ['GET'])]
    public function index(LicenceRepository $licenceRepository, SeasonRepository $seasonRepository, Request $request): Response
    {
        
        $seasons = $seasonRepository->findBy([], ['name' => 'ASC']);

        $currentSeason = $seasonRepository->findOneBy(['isCurrentSeason' => true]);
        // si la saison a été transmise dans la barre
        if ($request->query->get('saison') != null) {
            $selectedSeason = intval($request->query->get('saison'));
            $selectedSeason = $seasonRepository->findOneBy(['id' =>  $selectedSeason]);
        } else {
            $selectedSeason = $currentSeason;
        }

        // si la current season est bien définie, on va afficher les licences de cette saison : sinon on afiche tout
        $licences = $currentSeason ? $licenceRepository->findBy(['season' => $selectedSeason]) : $licenceRepository->findAll();
        // sinon on affiche tour


        
        return $this->render('admin/licence/index.html.twig', [
            'licences' => $licences,
            'selectedSeason' => $selectedSeason,
            'seasons' => $seasons
        ]);
    }

    #[Route('/new', name: 'app_admin_licence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, LicenceRepository $licenceRepository, ClubRepository $clubRepository): Response
    {
        $licence = new Licence();
        $form = $this->createForm(LicenceType::class, $licence);
        $form->handleRequest($request);
        //dd($licenceRepository->findByExampleField());
        
        if ( $form->isSubmitted()) {
            $nbMinCategorie = $form->getData()->getCategory()->getNbMin();
            $nbMaxCategorie = $form->getData()->getCategory()->getNbMax();
            $nombreDanseursRensignes = count($form->getData()->getDanseurs());

            if ($nombreDanseursRensignes < $nbMinCategorie or $nombreDanseursRensignes > $nbMaxCategorie) {
                $form->get('danseurs')->addError(new FormError("Le nombre de danseurs ne respecte pas la catégorire => Min: $nbMinCategorie Max: $nbMaxCategorie"));
            }
        }
       
        if ($form->isSubmitted() && $form->isValid()) {
            // tester si je suis un club 
            // alors je dois enregistrer mon club dans la licence
            if (in_array('ROLE_CLUB', $this->getUser()->getRoles())) {
                $myClub = $clubRepository->findOneBy(['owner' => $this->getUser()]);
                $licence->setClub($myClub);
            }
            
            $season = $form->getData()->getSeason();
            // Dernier numéro de dossard de la saison !!

            $dernierDossard = $licenceRepository->findOneBy(['season' => $season], ['dossard' => 'DESC']);
            if ($dernierDossard != null) {
                $newDossard = $dernierDossard->getDossard() + 1;
            } else {
                $newDossard = 1; 
            }
           
            $licence->setDossard($newDossard);

            $entityManager->persist($licence);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_licence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/licence/new.html.twig', [
            'licence' => $licence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_licence_show', methods: ['GET'])]
    public function show(Licence $licence): Response
    {
        return $this->render('licence/show.html.twig', [
            'licence' => $licence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_licence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Licence $licence, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LicenceType::class, $licence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_licence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('licence/edit.html.twig', [
            'licence' => $licence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_licence_delete', methods: ['POST'])]
    public function delete(Request $request, Licence $licence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$licence->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($licence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_licence_index', [], Response::HTTP_SEE_OTHER);
    }
}
