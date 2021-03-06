<?php

namespace App\Form;

use App\Repository\CandidatRepository;
use App\Validator\Vote as VoteValidator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

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
                'label'=>false,
                'constraints' => [
                    new VoteValidator(),
                ],
            ]
        );

    }
}
