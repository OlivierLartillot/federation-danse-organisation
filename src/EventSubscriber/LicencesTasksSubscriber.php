<?php

namespace App\EventSubscriber;

use App\Repository\ClubRepository;
use App\Repository\LicenceRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LicencesTasksSubscriber implements EventSubscriberInterface
{


    public function __construct(private SeasonRepository $seasonRepository, 
                                private LicenceRepository $licenceRepository,
                                private TokenStorageInterface $tokenStorageInterface,   
                                private RequestStack $requestStack,
                                private Security $security,
                                private ClubRepository $clubRepository
                                ) 
    { }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $session = $this->requestStack->getSession();
        $currentSeason = $this->seasonRepository->findBy(['isCurrentSeason' => true]);
        $currentUser = $this->tokenStorageInterface->getToken() ? $this->tokenStorageInterface->getToken()->getUser() : null;

        //------- 0 => en cours 2 => rejetée -----------
        if ($currentUser != null) {
            // si le current user est un admin
            if ($this->security->isGranted('ROLE_SECRETAIRE')) {
                $licencesInProgress = count($this->licenceRepository->findBy(['season' => $currentSeason, 'status' => 0]));
                $session->set('licencesEnCours',  $licencesInProgress);
            } else if (in_array('ROLE_CLUB', $currentUser->getRoles())){
                // si le current user est un club je cherche son club 
                $myClub = $this->clubRepository->findOneBy(['owner' => $currentUser]);
                // je recupere et compte les licences rejetees de cette saison pour son club
                $licenceRejected = count($this->licenceRepository->findBy(['club' => $myClub, 'season' => $currentSeason, 'status' => 2]));
                $session->set('licencesRejetees',  $licenceRejected);
            }

        }

    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
