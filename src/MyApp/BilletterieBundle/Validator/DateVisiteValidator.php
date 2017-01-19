<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 07/12/2016
 * Time: 14:53
 */

namespace MyApp\BilletterieBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateVisiteValidator extends ConstraintValidator
{
    public function validate( $value, Constraint $constraint)
    {
        $dateReserv = $this->context->getRoot()->getData()->getDateReserv();
        $date1 = $dateReserv->format('d/m/Y');
        $date2 = $value->format('d/m/Y');
        if ($date2 < $date1) {
            $this->context->addViolation($constraint->message);
        }
    }
}