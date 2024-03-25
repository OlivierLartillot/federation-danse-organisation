<?php

namespace App\EventSubscriber;

use App\Repository\SettingsRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class SettingsSubscriber implements EventSubscriberInterface
{

    private $settingsRepository;

    public function __construct(SettingsRepository $settingsRepository,) {
        $this->settingsRepository = $settingsRepository;

    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $settingsObjects = $this->settingsRepository->findAll();
        $settings = $settingsObjects[0];
        $logo = $settings->getLogo() ? 'images/logo/'.$settings->getLogo().'' : '';
        $phone = $settings->getPhone() ? $settings->getPhone() : '';
        $email = $settings->getEmail() ? $settings->getEmail() : '';
        $adress =  $settings->getAdress() ? $settings->getAdress() : '';
        $facebook = $settings->getFacebook() ? '<a href="https://www.facebook.com/'.$settings->getFacebook().'"><i class="fab fa-facebook-f"></i></a>' : '';
        $twitter = $settings->getTwitter() ? '<a href="https://www.twitter.com/'.$settings->getTwitter().'"><i class="fab fa-twitter"></i></a>' : '';
        $linkedin = $settings->getLinkedin() ? '<a href="https://www.linkedin.com/'.$settings->getLinkedin().'"><i class="fab fa-linkedin-in"></i></a>' : '';
        $googleCard = $settings->getGoogleCard() ? '<a href="https://www.google.com/'.$settings->getGoogleCard().'"><i class="fa-brands fa-google"></i></a>' : '';

        

        $htmlContent = $event->getResponse()->getContent();

 // TODO remplacer une partie du contenu avec mon message => injecter du contenu
    $newHtmlContent = str_replace([
        'assets/img/logo.svg',
        '<a href="tel:+1631202-0088">+(163)-1202-0088</a>',
        '<a href="mailto:info@Danza.com">help24/7@gmail.com</a>',
        '835 Middle Country Rd, NY 11784, USA',
        '<a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>',
        '<a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>',
        '<a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>',
        '<a href="https://www.google.com/"><i class="fa-brands fa-google"></i></a>'
    ],[
        $logo,
        '<a href="tel:+'. $phone .' ">'. $phone .'</a>',
        '<a href="mailto:'.$email.'">'.$email.'</a>',
        $adress,
        $facebook,
        $twitter,
        $linkedin,
        $googleCard
    ],
    
    $htmlContent);


    
    $response = $event->getResponse();
    $response->setContent($newHtmlContent);



    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }
}
