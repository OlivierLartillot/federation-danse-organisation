<?php

namespace App\Controller\Admin;

use App\Entity\LicenceComment;
use App\Form\LicenceCommentType;
use App\Repository\LicenceCommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/licence/comment')]
class LicenceCommentController extends AbstractController
{
    #[Route('/', name: 'app_admin_licence_comment_index', methods: ['GET'])]
    public function index(LicenceCommentRepository $licenceCommentRepository): Response
    {
        return $this->render('admin/licence_comment/index.html.twig', [
            'licence_comments' => $licenceCommentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_licence_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $licenceComment = new LicenceComment();
        $form = $this->createForm(LicenceCommentType::class, $licenceComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($licenceComment);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_licence_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/licence_comment/new.html.twig', [
            'licence_comment' => $licenceComment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_licence_comment_show', methods: ['GET'])]
    public function show(LicenceComment $licenceComment): Response
    {
        return $this->render('admin/licence_comment/show.html.twig', [
            'licence_comment' => $licenceComment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_licence_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LicenceComment $licenceComment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LicenceCommentType::class, $licenceComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_licence_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/licence_comment/edit.html.twig', [
            'licence_comment' => $licenceComment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_licence_comment_delete', methods: ['POST'])]
    public function delete(Request $request, LicenceComment $licenceComment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$licenceComment->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($licenceComment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_licence_comment_index', [], Response::HTTP_SEE_OTHER);
    }
}
