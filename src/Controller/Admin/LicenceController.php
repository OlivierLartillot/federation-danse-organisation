<?php

namespace App\Controller\Admin;

use App\Entity\Licence;
use App\Entity\LicenceComment;
use App\Form\LicenceCommentType;
use App\Form\LicenceType;
use App\Repository\ClubRepository;
use App\Repository\LicenceRepository;
use App\Repository\SeasonRepository;
use App\Service\LicenceChecker;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/licence')]
class LicenceController extends AbstractController
{
    #[Route('', name: 'app_admin_licence_index', methods: ['GET'])]
    public function index(LicenceRepository $licenceRepository, SeasonRepository $seasonRepository, Request $request, ClubRepository $clubRepository, PaginatorInterface $paginator): Response
    {
        
        $seasons = $seasonRepository->findBy([], ['name' => 'ASC']);
        $currentSeason = $seasonRepository->findOneBy(['isCurrentSeason' => true]);
    
        // si on a cliqué sur un bouton de status
             if ($request->query->get('status') == 'cours') { $status = 0;}
        else if ($request->query->get('status') == 'validees') {$status = 1;} 
        else if ($request->query->get('status') == 'rejetees') { $status = 2;} 
        else {$status = 'all';}


        // si la saison a été transmise dans la barre
        if ($request->query->get('saison') != null) {
            $selectedSeason = intval($request->query->get('saison'));
            $selectedSeason = $seasonRepository->findOneBy(['id' =>  $selectedSeason]);
        } else {
            $selectedSeason = $currentSeason;
        }
        // si la current season est bien définie, on va afficher les licences de cette saison : sinon on afiche tout
        $licences = $currentSeason ? $licenceRepository->findBy(['season' => $selectedSeason], ['status' => 'ASC', 'category' => 'ASC']) : $licenceRepository->findAll();

        // si le club a été transmis dans la barre 
        if (($request->query->get('club') != null) && ($request->query->get('club') != 'all')) {
            $selectedClub = intval($request->query->get('club'));
            if ($status == 'all') {
                $licences = $currentSeason ? $licenceRepository->findBy(['season' => $selectedSeason, 'club' => $selectedClub,], ['status' => 'ASC', 'category' => 'ASC']) : $licenceRepository->findAll();
            } else {
                $licences = $currentSeason ? $licenceRepository->findBy(['season' => $selectedSeason, 'club' => $selectedClub, 'status' => $status], ['status' => 'ASC', 'category' => 'ASC']) : $licenceRepository->findAll();
            }
        } else {
            if ($status == 'all') {
                $licences = $currentSeason ? $licenceRepository->findBy(['season' => $selectedSeason], ['status' => 'ASC', 'category' => 'ASC']) : $licenceRepository->findAll();
            }else {
                $licences = $currentSeason ? $licenceRepository->findBy(['season' => $selectedSeason, 'status' => $status], ['status' => 'ASC', 'category' => 'ASC']) : $licenceRepository->findAll();
            }

        }

        // si tu es un club tu ne peux avoir acces qu'à la liste de tes licences
        if (in_array('ROLE_CLUB', $this->getUser()->getRoles())) {
            // cherche mon club
            $myClub = $clubRepository->findBy(['owner' => $this->getUser()]);
            // et mes licences en affichant en premier les rejetées
            if ($status == 'all') {
                $licences = $currentSeason ? $licenceRepository->findBy(['season' => $selectedSeason, 'club' => $myClub], ['status' => 'DESC', 'category' => 'ASC']) : [];
            } else {
                $licences = $currentSeason ? $licenceRepository->findBy(['season' => $selectedSeason, 'club' => $myClub, 'status' => $status], ['status' => 'DESC', 'category' => 'ASC']) : [];

            } 
        }


        $pagination = $paginator->paginate(
            $licences,
            $request->query->getInt('page', 1),
            25,
        );
        $pagination->setPageRange(3);

        return $this->render('admin/licence/index.html.twig', [
            'licences' => $pagination,
            'selectedSeason' => $selectedSeason,
            'seasons' => $seasons,
            'clubs' => $clubRepository->findBy([] , ['name' => 'ASC'])
        ]);
    }

    #[Route('/new', name: 'app_admin_licence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, LicenceRepository $licenceRepository, ClubRepository $clubRepository, LicenceChecker $licenceChecker): Response
    {
        $licence = new Licence();
        $form = $this->createForm(LicenceType::class, $licence);
        $form->handleRequest($request);
        //dd($licenceRepository->findByExampleField());
        
        if ($form->isSubmitted()) {
            // service qui se renseigne sur le nombre min et max de danseur
            // renvoie une erreur si ce n'est pas conforme
            $licenceChecker->checkDanseurNumber($form);
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

    #[Route('/{id}', name: 'app_admin_licence_show', methods: ['GET', 'POST'])]
    public function show(Licence $licence, Request $request, EntityManagerInterface $entityManager): Response
    {

        $licenceComment = new LicenceComment();
        $form = $this->createForm(LicenceCommentType::class, $licenceComment);
        $form->handleRequest($request);

        $comments = $licence->getLicenceComments();

        if ($form->isSubmitted() && $form->isValid()) {

            $licenceComment->setLicence($licence);
            $licenceComment->setUser($this->getUser());

            $entityManager->persist($licenceComment);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_licence_show', ['id' => $licence->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/licence/show.html.twig', [
            'licence' => $licence,
            'licence_comment' => $licenceComment,
            'form' => $form,
            'comments' => $comments,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_licence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Licence $licence, EntityManagerInterface $entityManager, LicenceChecker $licenceChecker): Response
    {
        $form = $this->createForm(LicenceType::class, $licence);
        $form->handleRequest($request);


        // si tu es un club et que la licence a le status validé tu ne peux pas la changer !!!
        // => EN FAIT SI  !!! Mais tu dois repasser les status en "en cours"
            /* 
            if((in_array('ROLE_CLUB', $this->getUser()->getRoles())) && $licence->getStatus() == 1) {
            return $this->redirectToRoute('app_admin_licence_index', [], Response::HTTP_SEE_OTHER);
            } 
            */

        if ($form->isSubmitted()) {
            // service qui se renseigne sur le nombre min et max de danseur
            // renvoie une erreur si ce n'est pas conforme
            $licenceChecker->checkDanseurNumber($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {

            if((in_array('ROLE_CLUB', $this->getUser()->getRoles())) && $licence->getStatus() == 1) {
                $licence->setStatus(0);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_licence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/licence/edit.html.twig', [
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

    #[Route('/validation/{id}', name: 'app_admin_licence_validation', methods: ['GET'])] 
    public function validationLicence(Request $request, Licence $licence, EntityManagerInterface $entityManager): Response
    {
        
        // je dois avoir les droits pour faire ca, sinon je rejette !!!
        //dd($request->get('validation'));
        if (in_array('ROLE_CLUB', $this->getUser()->getRoles())) {
           if ($request->get('validation') != 0) {
                return $this->redirectToRoute('app_admin_licence_index', [], Response::HTTP_SEE_OTHER);
           }
        }

        $licence->setStatus($request->get('validation'));
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_licence_show', [
            'id' => $licence->getId(), 
            'saison' => $request->query->get('saison'), 
            'club' => $request->query->get('club')
        ], Response::HTTP_SEE_OTHER);
        

    }

}
