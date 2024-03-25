<?php

namespace App\Controller\Admin;

use App\Entity\Settings;
use App\Form\SettingsType;
use App\Repository\SettingsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/settings')]
class SettingsController extends AbstractController
{
    /*     
    #[Route('/', name: 'app_settings_index', methods: ['GET'])]
    public function index(SettingsRepository $settingsRepository): Response
    {
        return $this->render('admin/settings/index.html.twig', [
            'settings' => $settingsRepository->findAll(),
        ]);
    } */

    /* On ne veux pas créer
    #[Route('/new', name: 'app_settings_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $setting = new Settings();
        $form = $this->createForm(SettingsType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($setting);
            $entityManager->flush();

            return $this->redirectToRoute('app_settings_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/settings/new.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    } 
    */

    #[Route('/{id}/edit', name: 'app_settings_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Settings $setting, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SettingsType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_settings_edit', ['id' => $request->get('id')], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/settings/edit.html.twig', [
            'setting' => $setting,
            'form' => $form,
        ]);
    }

}
