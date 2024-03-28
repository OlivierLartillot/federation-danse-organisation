<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ClubType extends AbstractType
{

    private $userRepository;
    private $tokenStorageInterface;

    public function __construct(UserRepository $userRepository, TokenStorageInterface $tokenStorageInterface) 
    {
        $this->userRepository = $userRepository;
        $this->tokenStorageInterface = $tokenStorageInterface;
    } 

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // préparation des données 
            // le select "owner" ne contient que les propriétaires de club
            $users = $this->userRepository->findBy([], ['username' => 'ASC']);
            $owners = [];
            foreach ($users as $user) {
                if (in_array('ROLE_CLUB', $user->getRoles())) {
                    $owners[] = $user;
                }
            }
            // les personnes pouvant accéder à l'édition du gestionnaire du club
            $tableauRolesAutorises = ['ROLE_HULK', 'ROLE_SUPERMAN'];
            $autorisation = false;
            // si je suis un club alors je n'ai pas la possibilité de changer l'owner
            // HULK => le super ADMIN
            // SUPERMAN => l'ADMIN
            if ($this->tokenStorageInterface->getToken() != null) {
                $user = $this->tokenStorageInterface->getToken()->getUser();
                foreach ($tableauRolesAutorises as $role) {
                    if (in_array($role, $user->getRoles())){ 
                        $autorisation = true;
                    }
                }
            }
        
        // Le formulaire
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'mb-5']
            ]);


        if ($autorisation) {
        $builder
            ->add('owner', ChoiceType::class, [
                    'label' => 'Gestionnaire',
                    'attr' => ['class' => 'mb-5'],
                    'choices'  => $owners,
                    'choice_label' => function (?User $user): string {
                        return $user ? ucfirst(strtolower($user->getFirstname())) .' '. ucfirst(strtolower($user->getLastname()))  : '';
                    },
                ]);
        }
            
        $builder
            ->add('correspondents', NULL, [
                'label' => 'Correspondant(s)',
                'row_attr' => ['class' => 'mb-5'],
                'help' => '*Dans le cas de plusieurs correspondants, séparez les par des virgules. ex: John Doeuf, Jean Peuplu, Thomas Toketchup',
                'help_attr' => ['class' => 'text-danger fst-italic']
            ])
            ->add('adress', NULL, [
                'label' => 'Adresse',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('phonePrimary', NULL, [
                'label' => 'Tel.(1)',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('phoneSecondary', NULL, [
                'label' => 'Tel.(2)',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('emailPrimary', NULL, [
                'label' => 'email(1)',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('emailSecondary', NULL, [
                'label' => 'email(2)',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('fax', NULL, [
                'label' => 'Fax',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('webSite', NULL, [
                'label' => 'Site Web',
                'attr' => ['class' => 'mb-5']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}
