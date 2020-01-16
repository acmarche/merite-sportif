<?php

namespace App\Form;

use App\Repository\CandidatRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $builder
            ->add(
                'candidat',
                CandidatHiddenType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'point',
                IntegerType::class,
                [
                    'attr' => ['min' => 0, 'max' => 2],
                    'required' => false,
                    'label' => 'Point(s) attribué(s)'
                ]
            );
    }
}
