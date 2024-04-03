<?php

namespace App\Form;

use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\Licence;
use App\Entity\Season;
use App\Repository\ClubRepository;
use App\Repository\LicenceRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChampionshipInscriptionsType extends AbstractType
{

    private $licencesRepository;
    private $danseurRepository;
    private $tokenStorageInterface;
    private $clubRepository;

    public function __construct(LicenceRepository $licenceRepository, TokenStorageInterface $tokenStorageInterface, ClubRepository $clubRepository) 
    {
        $this->licencesRepository = $licenceRepository;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->clubRepository = $clubRepository;
    } 

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        
        $iHaveRoleClub = $this->iHaveRoleClub();
        $currentUser =  $this->tokenStorageInterface->getToken()->getUser();
         
        if ($iHaveRoleClub) {
            $myClub = $this->clubRepository->findOneBy(['owner' => $currentUser]);
            
            $licences = $this->licencesRepository->findBy(['club' => $myClub]);
        } else {
            $licences = $this->licencesRepository->findAll();
        } 
       
        

        $builder
            ->add('licences', EntityType::class, [
                'class' => Licence::class,
                'multiple' => true,
                'expanded' => true, 
                'choice_label' => 'fullPresentation',
                'choices' => $licences,
                'autocomplete' => false,
                'label' => 'Danseurs',
                'row_attr' => ['class' => 'mb-5'],
                'mapped' => false
            ])

            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Championship::class,
        ]);
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
