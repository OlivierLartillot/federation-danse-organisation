<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /* ->add('imageFile', VichImageType::class, [
                'label' => 'Logo',

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
                        'mimeTypesMessage' => 'Please upload a valid file',
                    ]),
                    
                ],
                'row_attr' => ['class' => 'mb-5'],
                'help' => 'Ne changez pas le logo si vous ne savez pas ce que vous faites !'

            ]) */
            ->add('phone', null, [
                'label' => 'Télephone FDO',
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('email', null, [
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('adress', null, [
                'label' => 'Adresse',
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('facebook', null, [
                'help' => 'Ne marquer que la partie après facebook.com/ ! ',
                'help_attr' => ['class' => 'text-danger fst-italic'],
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('twitter', null, [
                'help' => 'Ne marquer que la partie après twitter.com/! ',
                'help_attr' => ['class' => 'text-danger fst-italic'],
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('linkedin', null, [
                'help' => 'Ne marquer que la partie après linkedin.com/ ! ',
                'help_attr' => ['class' => 'text-danger fst-italic'],
                'row_attr' => ['class' => 'mb-5']
            ])
            /*
            ->add('googleCard', null, [
                'help' => 'Ne marquer que la partie après googleCard.com/ ! ',
                'help_attr' => ['class' => 'text-danger fst-italic'],
                'row_attr' => ['class' => 'mb-5']
            ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}
