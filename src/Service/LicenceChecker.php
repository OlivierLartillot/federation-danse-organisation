<?php

namespace App\Service;

use Symfony\Component\Form\FormError;

class LicenceChecker
{

    /**
     * method checkDanseurNumber
     * on prend le form qui nous est envoyé et on check le nombre Min et Max de danseur
     * Si ce n'est pas respecté, on renvoie une erreur à l'utilisateur
     *
     * @param object $form
     * @return mixed soit une erreur, soit null
     */
    public function checkDanseurNumber(object $form): mixed
    {
        $nbMinCategorie = $form->getData()->getCategory()->getNbMin();
        $nbMaxCategorie = $form->getData()->getCategory()->getNbMax();
        $nombreDanseursRensignes = count($form->getData()->getDanseurs());

        if ($nombreDanseursRensignes < $nbMinCategorie or $nombreDanseursRensignes > $nbMaxCategorie) {
            return $form->get('danseurs')->addError(new FormError("Le nombre de danseurs ne respecte pas la catégorire => Min: $nbMinCategorie Max: $nbMaxCategorie"));
        }

        return null;
    }


}