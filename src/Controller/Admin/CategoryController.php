<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/categories')]
class CategoryController extends AbstractController
{
    #[Route('', name: 'app_admin_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository, Request $request): Response
    {

        // si la request club et renvoyé par l admin 
        if ($request->query->get('archived') == null) {
            $categories = $categoryRepository->findBy(['archived' => false], ['name' => 'ASC']);
        } else if (($request->query->get('archived') != null)  && ($request->query->get('archived') != 'all')) {
            $selectedArchivedStatus = $request->query->get('archived') == "false" ? false : true;
            $categories = $categoryRepository->findBy(['archived' => $selectedArchivedStatus], ['name' => 'ASC']);
        } else {
            // au cas ou ... on renvoie tout
            $categories = $categoryRepository->findBy([], ['name' => 'ASC']);
        }
            

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/new', name: 'app_admin_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_category_show', methods: ['GET'])]
    public function show(Category $category): Response
    {
        return $this->render('admin/category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }
    
    #[Route('/archived/{id}', name: 'app_admin_category_archived_traitement', methods: ['POST'])]
    public function categoryArchived(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('archived'.$category->getId(), $request->request->get('_token'))) {

            $category->isArchived() ? $category->setArchived(false) : $category->setArchived(true);
            $entityManager->flush();
            if (!$category->isArchived()) {
                $this->addFlash(
                    'success',
                    'La catégorie a été "désarchivée"'
                );
            }
             else{
                $this->addFlash(
                    'danger',
                    'La catégorie a été archivée'
                );
             }
        }

        return $this->redirectToRoute('app_admin_category_show', ['id' => $category->getId()], Response::HTTP_SEE_OTHER);
    }
    
    /* DELETE 
    #[Route('/{id}', name: 'app_admin_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_category_index', [], Response::HTTP_SEE_OTHER);
    } 
    */
}
