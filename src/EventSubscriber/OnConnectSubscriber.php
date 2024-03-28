<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
        // ...
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
