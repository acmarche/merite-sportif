<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Repository\CandidatRepository;
use App\Validator\Vote as VoteValidator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VotesType extends AbstractType
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
        $builder->add(
            'candidatures',
            CollectionType::class,
            [
                'entry_type' => VoteType::class,
                'constraints' => [
                    //    new VoteValidator(),
                ],
            ]
        );

    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }
}
