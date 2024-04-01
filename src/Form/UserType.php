<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{

    /** 
    * Ici la liste des roles qui ont le droit de modifier les utilisateurs 
    * Array 
    */
    const AUTORISATION_LIST = ['ROLE_HULK', 'ROLE_SUPERMAN'];

    public function __construct(private UserRepository $userRepository, private TokenStorageInterface $tokenStorageInterface)
    {
        $this->userRepository = $userRepository;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $currentUser = $this->tokenStorageInterface->getToken()->getUser();

        // si je suis dans la liste autorisée, je pourrai changer le role par exemple
        $autorisationRolesList = self::AUTORISATION_LIST;
        $manageUsersAuthorisation = false;
        foreach ($autorisationRolesList as $role) {
            if (in_array($role, $currentUser->getRoles())) {
                $manageUsersAuthorisation = true;
            }
        }
       

        $builder
            ->add('username', null, [
                'label' => 'Pseudo',
                'row_attr' => ['class' => 'mb-5'],
                'help' => "*Le pseudo servira à l'utilisateur à se connecter sur l'application",
                'help_attr' => ['class' => 'fst-italic'],
                // la personne ne peut pas changer son pseudo, sauf si c'est un admin
                'disabled' => !$manageUsersAuthorisation // si c est l'admin, il est a disable false  
            ]) 
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'row_attr' => ['class' => 'mb-5']
            ])  
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'row_attr' => ['class' => 'mb-5']
            ])  
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'row_attr' => ['class' => 'mb-5'],
                'required' => false,
                'constraints' => [
                    new Email([
                        'message' => 'Un email valide est nécessaire',
                    ]),
                ],
            ]);
          
            if ($manageUsersAuthorisation) {
                $builder
                    -> add('roles', ChoiceType::class, [
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
                        ]);
            }
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'options' => ['attr' => ['class' => 'password-field'], 'required' => false],
                'required' => false,
                'first_options'  => ['label' => 'Mot de passe*'],
                'second_options' => ['label' => 'Confirmez le mot de passe*'],
                'constraints' => [
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
