<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Categorie;
use App\Entity\Sport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

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
                'sport',
                EntityType::class,
                [
                    'class' => Sport::class,
                ]
            )
            ->add(
                'categorie',
                EntityType::class,
                [
                    'class' => Categorie::class,
                    'multiple' => false,
                    'expanded' => true,
                ]
            )
            ->add(
                'imageFile',
                VichImageType::class,
                [
                    'required' => false,
                    'label' => 'Image',
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
