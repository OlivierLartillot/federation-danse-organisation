<?php

namespace App\Controller\Admin;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use App\Service\SwitchCurrent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/saisons')]
class SeasonController extends AbstractController
{
    #[Route('/', name: 'app_admin_season_index', methods: ['GET'])]
    public function index(SeasonRepository $seasonRepository): Response
    {

        return $this->render('admin/season/index.html.twig', [
            'seasons' => $seasonRepository->findBy(['isArchived' => false]),
        ]);
    }

    #[Route('/archivees', name: 'app_admin_season_archived_seasons', methods: ['GET'])]
    public function archivedSeasons(SeasonRepository $seasonRepository): Response
    {
        return $this->render('admin/season/archived.html.twig', [
            'seasons' => $seasonRepository->findBy(['isArchived' => true]),
        ]);
    }

    #[Route('/new', name: 'app_admin_season_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SwitchCurrent $switchCurrentSeason): Response
    {
        $season = new Season();
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Il ne peut pas y avoir 2 saison Courantes en même temps !!!
            // si la saison du formulaire devient la courante il faut regarder tous les autres et passer à false les autres
            // ATENTION: Si on la met a false, il peut ne plus y avoir de saison courante
            $switchCurrentSeason->switchCurrentSeason($season);
            $entityManager->persist($season);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_season_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/season/new.html.twig', [
            'season' => $season,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_season_show', methods: ['GET'])]
    public function show(Season $season): Response
    {
        return $this->render('admin/season/show.html.twig', [
            'season' => $season,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_season_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Season $season, EntityManagerInterface $entityManager, SwitchCurrent $switchCurrentSeason): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Il ne peut pas y avoir 2 saison Courantes en même temps !!!
            // si la saison du formulaire devient la courante il faut regarder tous les autres et passer à false les autres
            // ATENTION: Si on la met a false, il peut ne plus y avoir de saison courante
            $switchCurrentSeason->switchCurrentSeason($season);

            $entityManager->flush();

            return $this->redirectToRoute('app_admin_season_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/season/edit.html.twig', [
            'season' => $season,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_season_delete', methods: ['POST'])]
    public function delete(Request $request, Season $season, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$season->getId(), $request->request->get('_token'))) {
            $entityManager->remove($season);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_season_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/archived/{id}', name: 'app_admin_season_archived', methods: ['POST'])]
    public function archived(Request $request, Season $season, EntityManagerInterface $entityManager): Response
    {


        if ($this->isCsrfTokenValid('archived'.$season->getId(), $request->request->get('_token'))) {

            $season->isArchived() ? $season->setIsArchived(false) : $season->setIsArchived(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_season_archived_seasons', [], Response::HTTP_SEE_OTHER);
    }
}
