<?php

namespace App\Form;

use App\Entity\Danseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class DanseurAutocompleteFieldType extends AbstractType
{


    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'class' => Danseur::class,
            'searchable_fields' => ['firstname', 'Lastname'],
            'multiple' => false,
            'constraints' => [
                new Count(min: 1, minMessage: 'Veuillez entrer des danseurs'),
            ],
            
        ]);
    }
    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
