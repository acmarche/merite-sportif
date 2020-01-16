<?php

namespace App\Form;

use App\Entity\Sport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class PropositionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('categorie')
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
                'sport',
                EntityType::class,
                [
                    'class' => Sport::class,
                ]
            );
    }

    public function getParent()
    {
        return CandidatType::class;
    }


}
