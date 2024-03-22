<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', NULL, [
                'label' => 'Nom',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('minAge', NULL, [
                'label' => 'Age minimum',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('maxAge', NULL, [
                'label' => 'Age maximum',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('nbMin', NULL, [
                'label' => 'Nombre Minimum',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('nbMax', NULL, [
                'label' => 'Nombre Maximum',
                'attr' => ['class' => 'mb-5']
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
