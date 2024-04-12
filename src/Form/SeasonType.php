<?php

namespace App\Form;

use App\Entity\Season;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', NULL, [
                'label' => 'Nom'
            ] )
            ->add('isCurrentSeason', NULL, [
                'label' => 'Saison Actuelle ?'
            ] )
            ->add('modifiedValidatedLicence', null, [
                'label' => 'Ne plus permettre la modification  par les clubs des licences déjà validées',
                'help' => '*Si cette case est cochée, l\'équipe FDO peut toujours modifier une licence. Astuce: pour permettre à un club de modifier une licence, vous pouvez la rejeter. N\'étant plus en status validée, elle sera donc à nouveau modifiable.',
                'help_attr' => ['class' => 'text-danger fst-italic']
            
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
