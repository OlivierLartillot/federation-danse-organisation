<?php

namespace App\Service;

use App\Repository\ChampionshipRepository;
use App\Repository\SeasonRepository;

class SwitchCurrent
{
    // $championshipRepository va mettre a jour les autres championnats
    private $championshipRepository;
    private $seasonRepository;


    public function __construct(ChampionshipRepository $championshipRepository, SeasonRepository $seasonRepository )
    {
        $this->championshipRepository = $championshipRepository;
        $this->seasonRepository = $seasonRepository;
    }

    /**
     * method switchCurrent
     * 
     * prend le championship courant et modifie les autres si celui ci devient le current !
     *
     * @param object $championship
     * @return void
     */
    public function switchCurrentChampionship(object $championship): void
    {

        if ($championship->isCurrentChampionship()) {
            $championnatsAvecCurrentTrue = $this->championshipRepository->findBy(['isCurrentChampionship' => true]);
            foreach ($championnatsAvecCurrentTrue as $championnatCourantDeLaBoucle) {
                if ($championnatCourantDeLaBoucle != $championship) {
                    $championnatCourantDeLaBoucle->setIsCurrentChampionship(false);
                }
                continue;
            }
        } 
        return;
    }

    /**
     * method switchCurrent
     * 
     * prend le championship courant et modifie les autres si celui ci devient le current !
     *
     * @param object $season
     * @return void
     */
    public function switchCurrentSeason(object $season): void
    {

        if ($season->isCurrentSeason()) {
            $saisonsAvecCurrentTrue = $this->seasonRepository->findBy(['isCurrentSeason' => true]);
            foreach ($saisonsAvecCurrentTrue as $saisonCourantDeLaBoucle) {
                if ($saisonCourantDeLaBoucle != $season) {
                    $saisonCourantDeLaBoucle->setIsCurrentSeason(false);
                }
                continue;
            }
        } 
        return;
    }

}