<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Danseur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class CategoriesAutocompleteFieldType extends AbstractType
{


    public function configureOptions(OptionsResolver $resolver): void
    {


        $resolver->setDefaults([
            'class' => Category::class,
            'searchable_fields' => ['name'],
            'label' => 'Choisi la catégorie',
            'multiple' => false,
            'constraints' => [
                new Count(max: 1, minMessage: 'Tu dois choisir une catégorie maximum !'),
            ],
            
        ]);
    }
    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
