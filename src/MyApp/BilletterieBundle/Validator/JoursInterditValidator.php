<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 09/12/2016
 * Time: 16:28
 */

namespace MyApp\BilletterieBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class JoursInterditValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $dateVisite = $value;
        $dateVisite2 = $dateVisite->format('d-m-Y');

        //---------- Extraction numéro du jour de la semaine -----------//
        $jrSem = date('w', strtotime($dateVisite2));

        if ($jrSem == 0 or $jrSem == 2)
        {
            $this->context->addViolation($constraint->message);
        }

        //---------------- Partie jours fériés ----------------//
        $dateVisite3 = $dateVisite->format('d/m');
        $tabJrsFeries = array('01/01','17/04','01/05','08/05','25/05','14/07','15/08','01/11','25/12');
       // $jrsTrouve = 'false';

        foreach ($tabJrsFeries as &$jrsferie)
        {
            if ($jrsferie == $dateVisite3){
                $this->context->addViolation($constraint->message2);
            }
        }
    }
}