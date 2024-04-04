<?php

namespace App\Form;

use Doctrine\ORM\QueryBuilder;
use App\Entity\Category;
use App\Entity\Club;
use App\Entity\Danseur;
use App\Entity\Licence;
use App\Entity\Season;
use App\Repository\ClubRepository;
use App\Repository\DanseurRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LicenceType extends AbstractType
{

    
    private $danseurRepository;
    private $tokenStorageInterface;
    private $clubRepository;

    public function __construct(DanseurRepository $danseurRepository, TokenStorageInterface $tokenStorageInterface, ClubRepository $clubRepository) 
    {
        $this->danseurRepository = $danseurRepository;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->clubRepository = $clubRepository;
    } 
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $danseurs = $this->getDanseursList();
        $iHaveRoleClub = $this->iHaveRoleClub();

        if (!$iHaveRoleClub) {
            $builder 
                ->add('club', EntityType::class, [
                'class' => Club::class,
                'label' => 'Club',
                'choice_label' => 'name',
                'row_attr' => ['class' => 'mb-5'],
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('club')
                        ->orderBy('club.name', 'ASC');
                },
                ]);
        }

        $builder
            /*             
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégorie',
                'choice_label' => 'categorieDescriptionText',
                'row_attr' => ['class' => 'mb-5']
                
            ]) */
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'multiple' => false,
                'choice_label' => 'categorieDescriptionText',
                'label' => 'Catégorie',
                'row_attr' => ['class' => 'mb-5'],
                'autocomplete' => true,
            ])
            /* ->add('archived') */
            /* ->add('danseurs', DanseurAutocompleteFieldType::class) */
            ->add('danseurs', EntityType::class, [
                'class' => Danseur::class,
                'multiple' => true,
                'choice_label' => 'fullname',
                'choices' => $danseurs,
                'autocomplete' => true,
                'label' => 'Danseurs',
                'row_attr' => ['class' => 'mb-5'],
            ])

            ->add('season', EntityType::class, [
                'class' => Season::class,
                'label' => 'Saison',
                'help' => '*La saison par défaut est la saison en cours renseignée dans le système',
                'help_attr' => ['class' => 'fst-italic'],
                'row_attr' => ['class' => 'mb-5'],
                'preferred_choices' => function (?Season $season): bool {
                    return $season->isCurrentSeason();
                },

            ])
            /* ->add('dossard') */
            /* ->add('status') */
            /* ->add('licenceComments', CollectionType::class, [
                'entry_type' => LicenceComment::class,
                'allow_add' => true,
            ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Licence::class,
        ]);
    }

    private function getDanseursList()
    {
        // les personnes pouvant accéder à l'édition du gestionnaire du club
        $tableauRolesAutorises = ['ROLE_HULK', 'ROLE_SUPERMAN'];
        $allDanseurs = false;
        $currentUser = $this->tokenStorageInterface->getToken()->getUser();
        foreach ($tableauRolesAutorises as $role) { 
            if (in_array($role, $currentUser->getRoles())){ 
                $allDanseurs = true;
            }
        }
        if ($allDanseurs) {
            $danseurs = $this->danseurRepository->findBy([], ['lastname' => 'ASC']);
        } else {
            $club = $this->clubRepository->findOneBy(['owner' => $currentUser]);
            $danseurs = $club->getDanseurs();
        }

        return $danseurs;
    }

    private function iHaveRoleClub(): bool 
    {
        $currentUser = $this->tokenStorageInterface->getToken()->getUser();
        $roleClub = false;
        if (in_array('ROLE_CLUB', $currentUser->getRoles())) {
            $roleClub = true;
        }

        return $roleClub;
    }


}
