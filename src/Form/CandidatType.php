<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add(
                'description',
                TextareaType::class,
                [

                ]
            )
            ->add(
                'palmares',
                TextareaType::class,
                [

                ]
            )
            ->add(
                'categories',
                EntityType::class,
                [
                    'class' => Categorie::class,
                    'multiple' => true,
                    'expanded' => true,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Candidat::class,
            ]
        );
    }
}
