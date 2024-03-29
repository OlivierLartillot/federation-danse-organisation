<?php

namespace App\Form;

use App\Entity\Danseur;
use App\Repository\DanseurRepository;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
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
            'label' => 'What sounds tasty?',
            'multiple' => true,
            'constraints' => [
                new Count(min: 1, minMessage: 'We need to eat *something*'),
            ],
            
        ]);
    }
    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
