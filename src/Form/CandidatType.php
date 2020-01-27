<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Categorie;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'label' => 'Nom du candidat ou de l\'équipe, club',
                ]
            )
            ->add(
                'prenom',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'Prénom'
                ]
            )
            ->add(
                'description',
                CKEditorType::class,
                [
                    'help' => 'Son parcours / Historique du candidat'
                ]
            )
            ->add(
                'palmares',
                CKEditorType::class,
                [

                ]
            )
            ->add(
                'sport',
                TextType::class,
                [
                    'label' => 'Sport',
                    'required' => true,
                    'help' => '(Trail - Jogging, Athlétisme, Judo, Basket-ball, Tennis de table, Football,...'
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
            )
            ->add(
                'validate',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'Validé'
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
