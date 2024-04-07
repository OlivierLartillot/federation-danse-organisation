<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserEditType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user')]
class UserController extends AbstractController
{
    /** 
    * Ici la liste des roles qui ont le droit de modifier les utilisateurs 
    * Array 
    */
    const AUTORISATION_LIST = ['ROLE_HULK', 'ROLE_SUPERMAN'];

    #[Route('', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, Request $request): Response
    {


        // Les personnes autorisees a acéder à la liste complète des utilisateurs
        $autorisationRolesList = self::AUTORISATION_LIST;
        $manageUsersAuthorisation = false;
        foreach ($autorisationRolesList as $role) {
            if (in_array($role, $this->getUser()->getRoles())) {
                $manageUsersAuthorisation = true;
            }
        }

        if ($manageUsersAuthorisation) {
            // si la request club et renvoyé par l admin 
            if ($request->query->get('archived') == null) {
                $users = $userRepository->findBy(['archived' => false]);
            } else if (($request->query->get('archived') != null)  && ($request->query->get('archived') != 'all')) {
                $selectedArchivedStatus = $request->query->get('archived') == "false" ? false : true;
                $users = $userRepository->findBy(['archived' => $selectedArchivedStatus]);
            } else {
                // au cas ou ... on renvoie tout
                $users = $userRepository->findBy([]);
            }
        } else {
            $users = $userRepository->findBy(['id' =>  $this->getUser()->getId()]);
        }


        return $this->render('admin/user/index.html.twig', [
            'users' => $users,
            'manageUsersAuthorisation' => $manageUsersAuthorisation
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword(
                $user, 
                $user->getPassword()
            );
            $user->setPassword($hashedPassword);


            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {

        // si je ne suis pas dans la liste autorisée, je ne peux voir que moi !
        $autorisationRolesList = self::AUTORISATION_LIST;
        $manageUsersAuthorisation = false;
        foreach ($autorisationRolesList as $role) {
            if (in_array($role, $this->getUser()->getRoles())) {
                $manageUsersAuthorisation = true;
            }
        }
        // si j'ai pas les droits de management et que c'est pas moi, on va me virer
        if (!$manageUsersAuthorisation) {
            if ($user != $this->getUser()) {
                // je te vire
                return throw $this->createAccessDeniedException();
            }
        }

        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher, UserRepository $userRepository): Response
    {

        // $initialPassword = $userRepository->find($user)->getPassword();
        // si je ne suis pas dans la liste autorisée, je ne peux voir que moi !
        $autorisationRolesList = self::AUTORISATION_LIST;
        $manageUsersAuthorisation = false;
        foreach ($autorisationRolesList as $role) {
            if (in_array($role, $this->getUser()->getRoles())) {
                $manageUsersAuthorisation = true;
            }
        }
        // si j'ai pas les droits de management et que c'est pas moi, on va me virer
        if (!$manageUsersAuthorisation) {
            if ($user != $this->getUser()) {
                // je te vire
                return throw $this->createAccessDeniedException();
            }
        }
        
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);
      
        if ($form->isSubmitted() && $form->isValid()) {

            
            if ($form->getData()->getPlainPassword() != null) {
                $newHashedPassword = $hasher->hashPassword(
                    $user, 
                    $form->getData()->getPlainPassword()
                ); 
                $userRepository->upgradePassword($user,  $newHashedPassword); 
            }

            $entityManager->flush();

            
            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }
    

        return $this->render('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
         
        ]);
    }
    
    #[Route('/archived/{id}', name: 'app_admin_user_archived_traitement', methods: ['POST'])]
    public function userArchived(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('archived'.$user->getId(), $request->request->get('_token'))) {

            $user->isArchived() ? $user->setArchived(false) : $user->setArchived(true);
            $entityManager->flush();
            if (!$user->isArchived()) {
                $this->addFlash(
                    'success',
                    'L\'utilisateur a été "désarchivée". Cela signifie qu\'il pourra à nouveau se connecter sur l\'applicaltion.'
                );
            }
             else{
                $this->addFlash(
                    'danger',
                    'L\'utilisateur a été archivée. Il ne pourra plus se connecter sur l\'application'
                );
             }
        }

        return $this->redirectToRoute('app_admin_user_show', ['id' => $user->getId()], Response::HTTP_SEE_OTHER);
    }
    
    /* 
    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    } 
    */
}
