<?php

namespace App\Form;

use App\Entity\Club;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', NULL, [
                'label' => 'Nom',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('correspondents', NULL, [
                'label' => 'Correspondant(s)',
                'row_attr' => ['class' => 'mb-5'],
                'help' => '*Dans le cas de plusieurs correspondants, séparez les par des virgules. ex: John Doeuf, Jean Peuplu, Thomas Toketchup',
                'help_attr' => ['class' => 'text-danger fst-italic']
            ] )
            ->add('adress', NULL, [
                'label' => 'Adresse',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('phonePrimary', NULL, [
                'label' => 'Tel.(1)',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('phoneSecondary', NULL, [
                'label' => 'Tel.(2)',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('emailPrimary', NULL, [
                'label' => 'email(1)',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('emailSecondary', NULL, [
                'label' => 'email(2)',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('fax', NULL, [
                'label' => 'Fax',
                'attr' => ['class' => 'mb-5']
            ] )
            ->add('webSite', NULL, [
                'label' => 'Site Web',
                'attr' => ['class' => 'mb-5']
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
