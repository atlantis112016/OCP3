<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 02/01/2017
 * Time: 16:40
 */

namespace MyApp\BilletterieBundle\Validator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LimiteHeureBilletValidator extends ConstraintValidator
{
     public function validate($value, Constraint $constraint)
    {
        $dateReserv = new \DateTime('now');
        $date1 = $dateReserv->format('d/m/Y');
        $date2 = $value->format('d/m/Y');
        $heureReserv = $dateReserv->format('H');
        if ($date1 == $date2 && $heureReserv >= '14' && $this->context->getRoot()->getData()->getTypeJournee() == 'Journee') {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}