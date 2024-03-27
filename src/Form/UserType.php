<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'row_attr' => ['class' => 'mb-5'],
                'help' => "Le pseudo servira à l'utilisateur à se connecter sur l'application",
                'help_attr' => ['class' => 'text-danger fst-italic'],
            ]) 
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'row_attr' => ['class' => 'mb-5']
            ])  
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'row_attr' => ['class' => 'mb-5']
            ])  
            ->add('roles', ChoiceType::class, [
                'label' => 'Role(s) de l\'utilisateur ?',
                'choices' => [
                    'Club' => 'ROLE_CLUB',
                    'Admin' => 'ROLE_SUPERMAN',
                    'Secrétaire' => 'ROLE_SECRETAIRE',
                    'Licences' => 'ROLE_LICENCE',
                    'Juge' => 'ROLE_JUGE',
                ],
                'multiple' => true,
                'expanded' => true,
            ])    
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => ['label' => 'Mot de passe*'],
                'second_options' => ['label' => 'Confirmez le mot de passe*'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Un mot de passe est nécessaire',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
