<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Club;
use App\Entity\Danseur;
use App\Entity\Licence;
use App\Entity\LicenceComment;
use App\Entity\Season;
use App\Repository\ClubRepository;
use App\Repository\DanseurRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

        $builder
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'label' => 'Nom',
                'row_attr' => ['class' => 'mb-5']
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Nom',
                'row_attr' => ['class' => 'mb-5']
                
            ])
            ->add('archived')
                /* ->add('danseurs', DanseurAutocompleteFieldType::class) */
            ->add('danseurs', EntityType::class, [
                'class' => Danseur::class,
                'multiple' => true,
                'choice_label' => 'fullname',
                'choices' => $danseurs,
                'autocomplete' => true,
                'label' => 'Nom',
                'row_attr' => ['class' => 'mb-5'],
            ])

            ->add('season', EntityType::class, [
                'class' => Season::class,
                'label' => 'Nom',
                'row_attr' => ['class' => 'mb-5']
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
}
