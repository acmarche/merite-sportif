<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Categorie;
use App\Repository\CandidatRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchCandidatType extends AbstractType
{
    /**
     * @var CandidatRepository
     */
    private $candidatRepository;

    public function __construct(CandidatRepository $candidatRepository)
    {
        $this->candidatRepository = $candidatRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sports = $this->candidatRepository->getAllSports();

        $builder
            ->add(
                'nom',
                SearchType::class,
                [
                    'required' => false
                ]
            )
            ->add(
                'categorie',
                EntityType::class,
                [
                    'class' => Categorie::class,
                    'required' => false
                ]
            )
            ->add(
                'sport',
                ChoiceType::class,
                [
                    'choices' => $sports,
                    'required' => false
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

            ]
        );
    }
}
