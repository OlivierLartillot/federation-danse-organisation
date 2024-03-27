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

use function PHPUnit\Framework\throwException;

#[Route('/admin/club')]
class ClubController extends AbstractController
{
    #[Route('/', name: 'app_admin_club_index', methods: ['GET'])]
    public function index(ClubRepository $clubRepository): Response
    {

        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou superieur tu peux accéder à la page
        if (!$this->isGranted('ROLE_SUPERMAN')) { return throw $this->createAccessDeniedException();}

        // ================= FIN AUTORISATIONS ==========================
        // ==============================================================

        return $this->render('admin/club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_club_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {


        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou superieur tu peux accéder à la page
        if (!$this->isGranted('ROLE_SUPERMAN')) { return throw $this->createAccessDeniedException();}

        // ================= FIN AUTORISATIONS ==========================
        // ==============================================================


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

    #[Route('/{id}', name: 'app_admin_club_show', methods: ['GET'])]
    public function show(Club $club): Response
    {

        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou superieur tu peux accéder à la page
        if (!$this->isGranted('ROLE_SUPERMAN')) { return throw $this->createAccessDeniedException();}

        // ================= FIN AUTORISATIONS ==========================
        // ==============================================================

        return $this->render('admin/club/show.html.twig', [
            'club' => $club,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_club_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou superieur tu peux accéder à la page
        if (!$this->isGranted('ROLE_CLUB')) { return throw $this->createAccessDeniedException();}

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

    #[Route('/{id}', name: 'app_admin_club_delete', methods: ['POST'])]
    public function delete(Request $request, Club $club, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getId(), $request->request->get('_token'))) {
            $entityManager->remove($club);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_club_index', [], Response::HTTP_SEE_OTHER);
    }
}
