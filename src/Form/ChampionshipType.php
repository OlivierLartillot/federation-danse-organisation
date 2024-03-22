<?php

namespace App\Form;

use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\Season;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChampionshipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('season', EntityType::class, [
                'label' => 'Saison',
                'class' => Season::class,
                'choice_label' => 'name',
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('championshipDate', null, [
                'label' => 'Date du championnat',
                'widget' => 'single_text',
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('championshipInscriptionsLimitDate', null, [
                'label' => 'Date Limite d\'inscription',
                'widget' => 'single_text',
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('organizingClub', EntityType::class, [
                'label' => 'Club organisateur',
                'class' => Club::class,
                'choice_label' => 'name',
                'row_attr' => ['class' => 'mb-5'],
            ])
            ->add('place', NULL, [
                'label' => 'Lieu',
                'row_attr' => ['class' => 'mb-5'],
                'help' => '*ex: Sennecy Le Grand',
                'help_attr' => ['class' => 'text-danger fst-italic']
            ] )
            ->add('number', NULL, [
                'label' => 'Numéro',
                'row_attr' => ['class' => 'mb-5']
            ] )
            ->add('isCurrentChampionship', NULL, [
                'label' => 'Championnat en cours',
                'row_attr' => ['class' => 'mb-5']
            ] )

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Championship::class,
        ]);
    }
}
