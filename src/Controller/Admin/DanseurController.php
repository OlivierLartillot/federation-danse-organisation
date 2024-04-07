<?php

namespace App\Controller\Admin;

use App\Entity\Danseur;
use App\Form\DanseurType;
use App\Repository\ClubRepository;
use App\Repository\DanseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/danseur')]
class DanseurController extends AbstractController
{
    #[Route('', name: 'app_admin_danseur_index', methods: ['GET'])]
    public function index(DanseurRepository $danseurRepository, Request $request, ClubRepository $clubRepository, PaginatorInterface $paginator): Response
    {
        $clubs = $clubRepository->findBy([], ['name' => 'ASC']);
        $selectedClub = 'all';
        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou superieur tu peux accéder à la page
        if (!$this->isGranted('ROLE_CLUB')) { return throw $this->createAccessDeniedException();}
        // ================= FIN AUTORISATIONS ==========================
        // ==============================================================

        // si tu es un club il faut récup que tes danseurs
        if (in_array('ROLE_CLUB', $this->getUser()->getRoles())) {
            // chercher le club de ce gestionnaire
            $club = $clubRepository->findOneBy(['owner' => $this->getUser()]);
            // récupérer ses danseurs non archives
            $listeDesDanseursNonArchives = [];
            foreach ($club->getDanseurs() as $danseur) {
                if (!$danseur->isArchived()) {
                    $listeDesDanseursNonArchives[] = $danseur;
                }
            }
            $danseurs = $listeDesDanseursNonArchives;
        }
        else {
            
            // si la request club et renvoyé par l admin 
            if (($request->query->get('club') != null)  && ($request->query->get('club') != 'all')) {
                $selectedClub = intval($request->query->get('club'));
                $club = $clubRepository->findOneBy(['id' => $selectedClub], ['name' => 'ASC']);
                $danseurs = $danseurRepository->findBy(['archived' => false, 'club' => $club]);

            } else {
                $danseurs = $danseurRepository->findBy(['archived' => false]);
            }
            
        }

        $pagination = $paginator->paginate(
            $danseurs,
            $request->query->getInt('page', 1),
            25,
        );
        $pagination->setPageRange(3);

        return $this->render('admin/danseur/index.html.twig', [
            'danseurs' => $pagination,
            'identifiant_route' => 'index',
            'clubs' =>  $clubs,
            'selectedClub' => $selectedClub,
        ]);
    }

    #[Route('/archives', name: 'app_admin_danseur_archived', methods: ['GET'])]
    public function archiveDanseurs(DanseurRepository $danseurRepository): Response
    {

        
        return $this->render('admin/danseur/index.html.twig', [
            'danseurs' => $danseurRepository->findBy(['archived' => true]),
            'identifiant_route' => 'archives'
        ]);
    }

    #[Route('/new', name: 'app_admin_danseur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ClubRepository $clubRepository): Response
    {
        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou superieur tu peux accéder à la page
        if (!$this->isGranted('ROLE_CLUB')) { return throw $this->createAccessDeniedException();}
        // ================= FIN AUTORISATIONS ==========================
        // ==============================================================

        $danseur = new Danseur();
        $form = $this->createForm(DanseurType::class, $danseur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Si je suis admin de mon club
            if (in_array('ROLE_CLUB',$this->getUser()->getRoles())) {
                // recherche le club de cette personne
                $monClub = $clubRepository->findOneBy(['owner' => $this->getUser()]);
                // enregistre le club automatiquement
                $danseur->setClub($monClub);
            }

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
    public function show(Danseur $danseur, ClubRepository $clubRepository): Response
    {

        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou superieur tu peux accéder à la page
        if (!$this->isGranted('ROLE_CLUB')) { return throw $this->createAccessDeniedException();}

        // si tu es un club il faut récup que tes danseurs
        if (in_array('ROLE_CLUB', $this->getUser()->getRoles())) {
            // chercher le club de ce gestionnaire
            $club = $clubRepository->findOneBy(['owner' => $this->getUser()]);
            // si ce danseur n est pas dans ton club on te vire 
            if ($danseur->getClub() != $club) {
                return $this->redirectToRoute('app_admin_danseur_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        // ================= FIN AUTORISATIONS ==========================
        // ==============================================================

        return $this->render('admin/danseur/show.html.twig', [
            'danseur' => $danseur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_danseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Danseur $danseur, EntityManagerInterface $entityManager, ClubRepository $clubRepository): Response
    {

        // ================== AUTORISATIONS =============================
        // ==============================================================
        // si tu es un club ou superieur tu peux accéder à la page
        if (!$this->isGranted('ROLE_CLUB')) { return throw $this->createAccessDeniedException();}

        // si tu es un club il faut récup que tes danseurs
        if (in_array('ROLE_CLUB', $this->getUser()->getRoles())) {
            // chercher le club de ce gestionnaire
            $club = $clubRepository->findOneBy(['owner' => $this->getUser()]);
            // si ce danseur n est pas dans ton club on te vire 
            if ($danseur->getClub() != $club) {
                return $this->redirectToRoute('app_admin_danseur_index', [], Response::HTTP_SEE_OTHER);
            }
        }
        // ================= FIN AUTORISATIONS ==========================
        // ==============================================================

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

    #[Route('/archived/{id}', name: 'app_admin_danseur_archived_traitement', methods: ['POST'])]
    public function archived(Request $request, Danseur $danseur, EntityManagerInterface $entityManager): Response
    {


        if ($this->isCsrfTokenValid('archived'.$danseur->getId(), $request->request->get('_token'))) {

            $danseur->isArchived() ? $danseur->setArchived(false) : $danseur->setArchived(true);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_danseur_archived', [], Response::HTTP_SEE_OTHER);
    }

    /*     
        #[Route('/{id}', name: 'app_admin_danseur_delete', methods: ['POST'])]
        public function delete(Request $request, Danseur $danseur, EntityManagerInterface $entityManager): Response
        {
            if ($this->isCsrfTokenValid('delete'.$danseur->getId(), $request->getPayload()->get('_token'))) {
                $entityManager->remove($danseur);
                $entityManager->flush();
            }

            return $this->redirectToRoute('app_admin_danseur_index', [], Response::HTTP_SEE_OTHER);
        } 
    */
}
