<?php

namespace App\Controller\Admin;

use App\Entity\Danseur;
use App\Form\DanseurType;
use App\Repository\DanseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/danseur')]
class DanseurController extends AbstractController
{
    #[Route('/', name: 'app_admin_danseur_index', methods: ['GET'])]
    public function index(DanseurRepository $danseurRepository): Response
    {
        return $this->render('admin/danseur/index.html.twig', [
            'danseurs' => $danseurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_danseur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $danseur = new Danseur();
        $form = $this->createForm(DanseurType::class, $danseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($danseur);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_danseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/danseur/new.html.twig', [
            'danseur' => $danseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_danseur_show', methods: ['GET'])]
    public function show(Danseur $danseur): Response
    {
        return $this->render('admin/danseur/show.html.twig', [
            'danseur' => $danseur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_danseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Danseur $danseur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DanseurType::class, $danseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_danseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/danseur/edit.html.twig', [
            'danseur' => $danseur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_danseur_delete', methods: ['POST'])]
    public function delete(Request $request, Danseur $danseur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$danseur->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($danseur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_danseur_index', [], Response::HTTP_SEE_OTHER);
    }
}
