<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 07/12/2016
 * Time: 14:26
 */

namespace MyApp\BilletterieBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateAnnivValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $date1 = new \DateTime('now');
        if ($value >= $date1) {
           // $this->context->addViolation($constraint->message);
            $this->context
            ->buildViolation($constraint->message)
                ->setParameters(array('%string%' => $value))
                ->addViolation()
            ;
        }
    }

}