<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class PropositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('categorie')
            ->remove('sport')
            ->remove('validate')
            ->add(
                'description',
                TextareaType::class,
                [
                    'help' => 'Son parcours / Historique du candidat',
                    'attr' => ['rows' => 5]
                ]
            )
            ->add(
                'palmares',
                TextareaType::class,
                [
                    'attr' => ['rows' => 5]
                ]
            )
            ->add(
                'sportTemporaire',
                TextType::class,
                [
                    'label' => 'Sport',
                    'required' => true,
                    'help' => '(Trail - Jogging, Athlétisme, Judo, Basket-ball, Tennis de table, Football,...'
                ]
            );
    }

    public function getParent()
    {
        return CandidatType::class;
    }


}
