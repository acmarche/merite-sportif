<?php

namespace App\Form;

use App\Repository\CandidatRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CandidatHiddenType extends AbstractType
{
    /**
     * @var CandidatToNumberTransformer
     */
    private $transformer;

    public function __construct(CandidatToNumberTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'invalid_message' => 'The selected candidat does not exist',
            ]
        );
    }

    public function getParent()
    {
        return HiddenType::class;
    }


}
