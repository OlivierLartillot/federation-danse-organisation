<?php

namespace App\Controller\Admin;

use App\Entity\Club;
use App\Form\ClubType;
use App\Repository\ClubRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use function PHPUnit\Framework\throwException;

#[Route('/admin/club')]
class ClubController extends AbstractController
{
    #[Route('/', name: 'app_admin_club_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository, Request $request): Response
    {

        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou DT et >
        // !!! tu ne peux pas dire !in_array('ROLE_CLUB'....) d'ou le passage par $rights
        $rights = false;
        $rights = in_array('ROLE_CLUB', $this->getUser()->getRoles()) || $rights;
        $rights = $this->isGranted('ROLE_DIRECTEUR_TECHNIQUE') || $rights;
        if (!$rights) {
            return throw $this->createAccessDeniedException();
        }
        // ================= FIN AUTORISATIONS ==========================
        // ==============================================================


        // droit modif et création
        $droitACreerUnCLub = true; 
        // si tu es un club il faut que ce soit le tiens pour le modifier
        if (in_array('ROLE_CLUB', $this->getUser()->getRoles())) {
            // cherche mon club
            $clubs = $clubRepository->findBy(['owner' => $this->getUser()]);
            $droitACreerUnCLub = false; 
        } else {
            // si la request club et renvoyé par l admin 
            if ($request->query->get('archived') == null) {
                $clubs = $clubRepository->findBy(['archived' => false], ['name' => 'ASC']);
            } else if (($request->query->get('archived') != null)  && ($request->query->get('archived') != 'all')) {
                $selectedArchivedStatus = $request->query->get('archived') == "false" ? false : true;
                $clubs = $clubRepository->findBy(['archived' => $selectedArchivedStatus], ['name' => 'ASC']);
            } else {
                // au cas ou ... on renvoie tout
                $clubs = $clubRepository->findBy([], ['name' => 'ASC']);
            }
           
        }

        return $this->render('admin/club/index.html.twig', [
            'clubs' => $clubs,
            'droitACreerUnCLub' => $droitACreerUnCLub,
        ]);
    }

    #[IsGranted('ROLE_DIRECTEUR_TECHNIQUE')]
    #[Route('/new', name: 'app_admin_club_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($club);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/club/new.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_DIRECTEUR_TECHNIQUE')]
    #[Route('/{id}', name: 'app_admin_club_show', methods: ['GET'])]
    public function show(Club $club): Response
    {

        return $this->render('admin/club/show.html.twig', [
            'club' => $club,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou DT et >
        // !!! tu ne peux pas dire !in_array('ROLE_CLUB'....) d'ou le passage par $rights
        $rights = false;
        $rights = in_array('ROLE_CLUB', $this->getUser()->getRoles()) || $rights;
        $rights = $this->isGranted('ROLE_DIRECTEUR_TECHNIQUE') || $rights;
        if (!$rights) {
            return throw $this->createAccessDeniedException();
        }

        // si tu es un club il faut que ce soit le tiens pour le modifier
        if (in_array('ROLE_CLUB', $this->getUser()->getRoles())) {
            // si le club courant n'est pas le tiens c est pas bon
            if ($club->getOwner() != $this->getUser())
            return throw $this->createAccessDeniedException();
        }
        // ================= FIN AUTORISATIONS ==========================
        // ==============================================================

        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_club_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/club/edit.html.twig', [
            'club' => $club,
            'form' => $form,
        ]);
    }


    #[Route('/archived/{id}', name: 'app_admin_club_archived_traitement', methods: ['POST'])]
    public function categoryArchived(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('archived'.$club->getId(), $request->request->get('_token'))) {

            $club->isArchived() ? $club->setArchived(false) : $club->setArchived(true);
            $entityManager->flush();

            if (!$club->isArchived()) {
                $this->addFlash(
                    'success',
                    'Le club a été "désarchivé"'
                );
            }
             else{
                $this->addFlash(
                    'danger',
                    'Le club a été archivé'
                );
             }

        }

        return $this->redirectToRoute('app_admin_club_show', ['id' => $club->getId()], Response::HTTP_SEE_OTHER);
    }

    /* Supprimer un club implique les relations user, danseurs, licence etc     
    #[Route('/{id}', name: 'app_admin_club_delete', methods: ['POST'])]
    public function delete(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $entityManager->remove($club);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_club_index', [], Response::HTTP_SEE_OTHER);
    } */
}
