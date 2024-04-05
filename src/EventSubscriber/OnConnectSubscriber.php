<?php

namespace App\EventSubscriber;

use App\Repository\LicenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class OnConnectSubscriber implements EventSubscriberInterface
{

    private $entityManagerInterface; 

    public function __construct(EntityManagerInterface $entityManagerInterface) 
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    public function onLoginSuccessEvent(LoginSuccessEvent $event): void
    {
        // Enregistrement de la derniere connexion
        $now = new \DateTimeImmutable('now');
        $connectedUser = $event->getUser();
        $lastConnection = $connectedUser->setLastConnection($now);
        $this->entityManagerInterface->persist($lastConnection);
        $this->entityManagerInterface->flush();

    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'onLoginSuccessEvent',
        ];
    }
}
