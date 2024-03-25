<?php

namespace App\Controller\Admin;

use App\Entity\OrganizationTeam;
use App\Form\OrganizationTeamType;
use App\Repository\OrganizationTeamRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/organization/team')]
class OrganizationTeamController extends AbstractController
{
    #[Route('/', name: 'app_admin_organization_team_index', methods: ['GET'])]
    public function index(OrganizationTeamRepository $organizationTeamRepository): Response
    {
        return $this->render('admin/organization_team/index.html.twig', [
            'organization_teams' => $organizationTeamRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_organization_team_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $organizationTeam = new OrganizationTeam();
        $form = $this->createForm(OrganizationTeamType::class, $organizationTeam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($organizationTeam);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_organization_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/organization_team/new.html.twig', [
            'organization_team' => $organizationTeam,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_organization_team_show', methods: ['GET'])]
    public function show(OrganizationTeam $organizationTeam): Response
    {
        return $this->render('admin/organization_team/show.html.twig', [
            'organization_team' => $organizationTeam,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_organization_team_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OrganizationTeam $organizationTeam, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OrganizationTeamType::class, $organizationTeam);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $entityManager->flush();

            return $this->redirectToRoute('app_admin_organization_team_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/organization_team/edit.html.twig', [
            'organization_team' => $organizationTeam,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_organization_team_delete', methods: ['POST'])]
    public function delete(Request $request, OrganizationTeam $organizationTeam, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$organizationTeam->getId(), $request->request->get('_token'))) {
            $entityManager->remove($organizationTeam);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_organization_team_index', [], Response::HTTP_SEE_OTHER);
    }
}
