<?php

namespace App\Form;

use App\Entity\Championship;
use App\Entity\Club;
use App\Entity\Licence;
use App\Entity\Season;
use App\Repository\ClubRepository;
use App\Repository\LicenceRepository;
use App\Repository\SeasonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ChampionshipInscriptionsType extends AbstractType
{

    private $licencesRepository;
    private $seasonRepository;
    private $tokenStorageInterface;
    private $clubRepository;

    public function __construct(LicenceRepository $licenceRepository, TokenStorageInterface $tokenStorageInterface, ClubRepository $clubRepository, SeasonRepository $seasonRepository) 
    {
        $this->licencesRepository = $licenceRepository;
        $this->tokenStorageInterface = $tokenStorageInterface;
        $this->clubRepository = $clubRepository;
        $this->seasonRepository = $seasonRepository;
    } 

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        
        // si c est un club - propriété de l'entité licence => fullPresentation
        // si c est un membre fdo - propriété de l'entité licence =>fullPresentationWithClub
            // les clubs doivent arriver dans l 'ordre alphabétique c'est plus simple !


        $iHaveRoleClub = $this->iHaveRoleClub();
        $currentUser =  $this->tokenStorageInterface->getToken()->getUser();
        $currentSeason = $this->seasonRepository->findOneBy(['isCurrentSeason' => true]);
         
        if ($iHaveRoleClub) {
            $myClub = $this->clubRepository->findOneBy(['owner' => $currentUser]);
            $licences = $this->licencesRepository->findValidateLicencesByCurrentSeasonAndClubOrderByCategories($currentSeason, $myClub, true);
            $choiceLabel = 'fullPresentation';
           
        } else {
            $licences = $this->licencesRepository->findValidateLicencesByCurrentSeasonAndClubOrderByCategories($currentSeason, null, false);
            $choiceLabel = 'fullPresentationWithClub';
        } 
       
        $builder
            ->add('licences', EntityType::class, [
                'class' => Licence::class,
                'multiple' => true,
                'expanded' => true, 
                'choice_label' => $choiceLabel,
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
