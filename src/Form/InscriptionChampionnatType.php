<?php

namespace App\Form;

use App\Entity\Championship;
use App\Entity\InscriptionChampionnat;
use App\Entity\Licence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionChampionnatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('licence', EntityType::class, [
                'class' => Licence::class,
                'choice_label' => 'id',
                'expanded' => true,
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InscriptionChampionnat::class,
        ]);
    }
}
