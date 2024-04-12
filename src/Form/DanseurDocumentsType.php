<?php

namespace App\Form;

use App\Entity\danseur;
use App\Entity\DanseurDocuments;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DanseurDocumentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
/*             ->add('danseur', EntityType::class, [
                'label' => 'Danseur(se)',
                'label_attr' => ['class' => 'fs-3 mb-3 mt-4'],
                'class' => danseur::class,
                'choice_label' => 'getFullName',
            ]) */
            ->add('identityFile', VichImageType::class, [
                'label' => 'Identité',
                'label_attr' => ['class' => 'fs-3 mb-3 mt-4'],
                // unmapped means that this field is not associated to any entity property
                'mapped' => true,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'row_attr' => ['class' => 'mb-3'],

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image document',
                    ])
                ],
            ])
            ->add('maxValidityIdentityDate', null, [
                'label' => 'Date de validité du documents d\'identité',
                'widget' => 'single_text',
                'row_attr' => ['class' => 'mb-5'],
            ])
            ->add('medicalCertificateFile', VichImageType::class, [
                'label' => 'Certificat Medical',
                'label_attr' => ['class' => 'fs-3 mb-3'],
                // unmapped means that this field is not associated to any entity property
                'mapped' => true,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                'row_attr' => ['class' => 'mb-3'],

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])            
            ->add('maxValidityCertificateDate', null, [
                'label' => 'Date de validité du certificat médical',
                'widget' => 'single_text',
                'row_attr' => ['class' => 'mb-5'],
            ])
            ->add('photographicPermissionFile', VichImageType::class, [
                'label' => 'Permission photographique',
                'label_attr' => ['class' => 'fs-3 mb-3'],
                'row_attr' => ['class' => 'mb-3'],

                // unmapped means that this field is not associated to any entity property
                'mapped' => true,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->add('maxValidityPhotographicPermissionDate', null, [
                'label' => 'Date de validité de la permission photographique',
                'widget' => 'single_text',
                'row_attr' => ['class' => 'mb-5'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DanseurDocuments::class,
        ]);
    }
}
