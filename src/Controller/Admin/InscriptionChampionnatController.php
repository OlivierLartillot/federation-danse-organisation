<?php

namespace App\Controller\Admin;

use App\Entity\InscriptionChampionnat;
use App\Form\InscriptionChampionnatType;
use App\Repository\InscriptionChampionnatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/inscription/championnat')]
class InscriptionChampionnatController extends AbstractController
{
    #[Route('/', name: 'app_admin_inscription_championnat_index', methods: ['GET'])]
    public function index(InscriptionChampionnatRepository $inscriptionChampionnatRepository): Response
    {
        return $this->render('admin/inscription_championnat/index.html.twig', [
            'inscription_championnats' => $inscriptionChampionnatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_inscription_championnat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $inscriptionChampionnat = new InscriptionChampionnat();
        $form = $this->createForm(InscriptionChampionnatType::class, $inscriptionChampionnat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($inscriptionChampionnat);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_inscription_championnat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/inscription_championnat/new.html.twig', [
            'inscription_championnat' => $inscriptionChampionnat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_inscription_championnat_show', methods: ['GET'])]
    public function show(InscriptionChampionnat $inscriptionChampionnat): Response
    {
        return $this->render('admin/inscription_championnat/show.html.twig', [
            'inscription_championnat' => $inscriptionChampionnat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_inscription_championnat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, InscriptionChampionnat $inscriptionChampionnat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InscriptionChampionnatType::class, $inscriptionChampionnat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_inscription_championnat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/inscription_championnat/edit.html.twig', [
            'inscription_championnat' => $inscriptionChampionnat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_inscription_championnat_delete', methods: ['POST'])]
    public function delete(Request $request, InscriptionChampionnat $inscriptionChampionnat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inscriptionChampionnat->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($inscriptionChampionnat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_inscription_championnat_index', [], Response::HTTP_SEE_OTHER);
    }
}
