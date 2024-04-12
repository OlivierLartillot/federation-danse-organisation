<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Entity\Danseur;
use App\Entity\DanseurDocuments;
use App\Form\DanseurDocumentsType;
use App\Repository\DanseurDocumentsRepository;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/danseurs/documents')]
class DanseurDocumentsController extends AbstractController
{
    #[Route('/', name: 'app_admin_danseur_documents_index_liste', methods: ['GET'])]
    public function index(DanseurDocumentsRepository $danseurDocumentsRepository): Response
    {

        return $this->render('admin/danseur_documents/index.html.twig', [
            'danseur_documents' => $danseurDocumentsRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_admin_danseur_documents_new', methods: ['GET', 'POST'])]
    public function new(Danseur $danseur, Request $request, EntityManagerInterface $entityManager): Response
    {
        // si je suis un club et que ce danseur n'est pas à moi ! je me fais virer
        if (in_array('ROLE_CLUB', $this->getUser()->getRoles() )) {
            if ($danseur->getClub()->getOwner() != $this->getUser()) {
                return throw $this->createAccessDeniedException();
            }
        }
        
        // si ce danseur a deja des documents, il faut édit
        if ($danseur->getDanseurDocuments() != null) {
            return $this->redirectToRoute('app_admin_danseur_index', [], Response::HTTP_SEE_OTHER);
        }

        $danseurDocument = new DanseurDocuments();
        $form = $this->createForm(DanseurDocumentsType::class, $danseurDocument);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // On ne peut pas changer l'id du danseur dans l'adresse grace au form symfony
            $danseurDocument->setDanseur($danseur) ;

            $entityManager->persist($danseurDocument);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Ces documents ont été ajoutés avec succès'
            );

            return $this->redirectToRoute('app_admin_danseur_documents_edit', ['id' => $danseur->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/danseur_documents/new.html.twig', [
            'danseur_document' => $danseurDocument,
            'form' => $form,
            'danseur' => $danseur
        ]);
    }

    #[Route('/{id}', name: 'app_admin_danseur_documents_show', methods: ['GET'])]
    public function show(DanseurDocuments $danseurDocument): Response
    {
        return $this->render('admin/danseur_documents/show.html.twig', [
            'danseur_document' => $danseurDocument,
        ]);
    }

    #[IsGranted('ROLE_LICENCE')]
    #[Route('/validate/{id}', name: 'app_admin_danseur_documents_validate', methods: ['GET'])]
    public function validate(Danseur $danseur, SeasonRepository $seasonRepository, EntityManagerInterface $entityManager, Request $request): Response
    {


        $validation = $request->get('validation');

        
        //récupérer la saison en cours  
        $currentSeason = $seasonRepository->findOneBy(['isCurrentSeason' => true]);

        // si current season n esxiste pas redirige avec flash
        if (!$currentSeason) {
            $this->addFlash(
                'danger',
                'Tu dois définir une saison en cours pour pouvoir valider ces documents.'
            );
        } else {
            // valider les papiers
            if ($validation == '1') {
                $danseur->getDanseurDocuments()->setValidatedForSeason($currentSeason);
                $text = 'validés';
                $status = 'success';
            } else {
                $danseur->getDanseurDocuments()->setValidatedForSeason(null);
                $text = 'invalidés';
                $status = 'danger';

            }
            $entityManager->flush();
            $this->addFlash(
                $status,
                'Les papiers ont bien été ' . $text
            );

        }
        return $this->redirectToRoute('app_admin_danseur_show', ['id' => $danseur->getId()], Response::HTTP_SEE_OTHER);
    }



    #[Route('/{id}/edit', name: 'app_admin_danseur_documents_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DanseurDocuments $danseurDocument, EntityManagerInterface $entityManager): Response
    {


        $form = $this->createForm(DanseurDocumentsType::class, $danseurDocument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            $this->addFlash(
                'success',
                'Ces documents ont été modifiés avec succès.'
            );


            return $this->redirectToRoute('app_admin_danseur_documents_edit', ['id' => $danseurDocument->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/danseur_documents/edit.html.twig', [
            'danseur_document' => $danseurDocument,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_danseur_documents_delete', methods: ['POST'])]
    public function delete(Request $request, DanseurDocuments $danseurDocument, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$danseurDocument->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($danseurDocument);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_danseur_documents_index', [], Response::HTTP_SEE_OTHER);
    }
}
