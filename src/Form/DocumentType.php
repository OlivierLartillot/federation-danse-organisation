<?php

namespace App\Form;

use App\Entity\Document;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Dropzone\Form\DropzoneType;
use Symfony\Component\Validator\Constraints\File;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du document',
                'row_attr' => ['class' => 'mb-5'],
            ])
            ->add('description', null, [
                'row_attr' => ['class' => 'mb-5'],
                'help' => '*Non obligatoire: Vous pouvez ajouter une courte description qui apparaitra au dessus du lien de téléchargement.',
                'help_attr' => ['class' => 'text-danger fst-italic'],
                'required' => false
            ])
            ->add('documentPath', FileType::class, [
                'label' => 'Document',
                'mapped' => false,
                'required' => false,
                'data_class' => null,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'image/png',
                            'image/jpeg',
                            'image/jpg',
                        ],
                        'mimeTypesMessage' => 'Les documents autiorisés sont : Pdf ',
                    ])                    
                ],
                'row_attr' => ['class' => 'mb-5'],
                'attr' => ['class' => 'p-2'],

            ])
            ->add('apparitionOrder', NumberType::class, [
                'label' => 'Ordre d\'affichage',
                'row_attr' => ['class' => 'mb-5'],
                'required' => false,
                'help' => '*Non obligatoire: Si vous laissez vide, ce document sera affiché en dernier. Vous pourrez changer son ordre plus tard.',
                'help_attr' => ['class' => 'text-danger fst-italic']

            ])
            ->add('published')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
