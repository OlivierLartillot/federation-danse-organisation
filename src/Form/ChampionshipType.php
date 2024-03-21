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
                'class' => Season::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('championshipDate', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('championshipInscriptionsLimitDate', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('organizingClub', EntityType::class, [
                'class' => Club::class,
                'choice_label' => 'name',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('place')
            ->add('number')

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Championship::class,
        ]);
    }
}
