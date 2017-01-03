<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 02/01/2017
 * Time: 16:40
 */

namespace MyApp\BilletterieBundle\Validator;
use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */

class LimiteHeureBillet extends Constraint
{
    public $message = "Vous ne pouvez pas réserver de billet journée après 14 heure";
}