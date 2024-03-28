<?php

namespace App\Form;

use App\Entity\Club;
use App\Entity\Danseur;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DanseurType extends AbstractType
{

    private $tokenStorageInterface;

    public function __construct(TokenStorageInterface $tokenStorageInterface) 
    {
        $this->tokenStorageInterface = $tokenStorageInterface;
    } 

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // les personnes pouvant accéder au changement de club de ce danseur
        $tableauRolesAutorises = ['ROLE_HULK', 'ROLE_SUPERMAN'];
        $autorisation = false;
        if ($this->tokenStorageInterface->getToken() != null) {
            $user = $this->tokenStorageInterface->getToken()->getUser();
            foreach ($tableauRolesAutorises as $role) {
                if (in_array($role, $user->getRoles())){ 
                    $autorisation = true;
                }
            }
        }

        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'mb-5']
            ])
            ->add('birthday', null, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'attr' => ['class' => 'mb-5']
            ]);


            // si je suis admin j'ai accès a changer le club 
            // sinon le club sera automatiquement ajouté à la création 
            // par le club de l'utilisateur courant  
            
        if ($autorisation) {
        $builder
            ->add('club', EntityType::class, [
                'class' => Club::class,
                'attr' => ['class' => 'mb-5'],
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                },
            ]);
        }

        $builder->add('archived', CheckboxType::class, [
                'label' => 'Archiver ce danseur ?',
                'attr' => ['class' => 'mb-5'],
                'required' => false,
        ]);
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Danseur::class,
        ]);
    }
}
