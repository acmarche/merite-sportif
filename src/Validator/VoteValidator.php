<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class VoteValidator extends ConstraintValidator
{
    public function validate($points, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\Vote */

        if (null === $points || '' === $points) {
            return;
        }

        $count = count($points);
        if ($count === 3) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $points)
                ->addViolation();
        }
    }
}
