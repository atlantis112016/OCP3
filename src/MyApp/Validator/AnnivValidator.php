<?php
/**
 * Created by PhpStorm.
 * User: Fabienne Bernard
 * Date: 07/12/2016
 * Time: 09:50
 */
namespace MyApp\BilletterieBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AnnivValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $now = new \DateTime();
        if($value > $now){
            $this->context
                ->buildViolation($constraint->message)
                ->setParameters(array('%string%' => $value))
                ->addViolation();
        }
    }
}