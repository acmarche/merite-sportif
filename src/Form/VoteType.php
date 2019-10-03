<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Repository\CandidatRepository;
use App\Validator\Vote as VoteValidator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoteType extends AbstractType
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
        $categorie = $options['categorie'];

        $builder->add(
            'candidats',
            EntityType::class,
            [
                'class' => Candidat::class,
                'query_builder' => function (CandidatRepository $candidatRepository) use ($categorie) {
                    return $candidatRepository->getQueryBuilder($categorie);
                },
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'placeholder' => 'Sélectionnez au moins 1 candidat',
                'constraints' => [
                    new VoteValidator(),
                ],
            ]
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                'categorie',
            ]
        );
    }
}
