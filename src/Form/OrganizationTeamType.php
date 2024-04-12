<?php

namespace App\Form;

use App\Entity\OrganizationTeam;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class OrganizationTeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom Complet',
                'row_attr' => ['class' => 'mt-3 mb-5'],
                'help' => '*ex:John Doeuf',
                'help_attr' => ['class' => 'fst-italic'],

            ])
            ->add('organizationRole', null, [
                'label' => 'Role',
                'row_attr' => ['class' => 'mb-5'],
                'help' => '*ex:Président',
                'help_attr' => ['class' => 'fst-italic'],
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'Photo (png, jpeg, jpg)',

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
            ->add('facebook', null, [
                'help' => 'Ne marquer que la partie après facebook.com/ ! ',
                'help_attr' => ['class' => 'text-danger fst-italic'],
                'row_attr' => ['class' => 'mt-5 mb-5']
            ])
            ->add('twitter', null, [
                'help' => 'Ne marquer que la partie après twitter.com/! ',
                'help_attr' => ['class' => 'text-danger fst-italic'],
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('instagram', null, [
                'help' => 'Ne marquer que la partie après linkedin.com/ ! ',
                'help_attr' => ['class' => 'text-danger fst-italic'],
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('linkedin', null, [
                'help' => 'Ne marquer que la partie après linkedin.com/ ! ',
                'help_attr' => ['class' => 'text-danger fst-italic'],
                'row_attr' => ['class' => 'mb-5']
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrganizationTeam::class,
        ]);
    }
}
