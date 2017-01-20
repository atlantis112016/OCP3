<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 07/12/2016
 * Time: 14:52
 */

namespace MyApp\BilletterieBundle\Validator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class DateVisite extends Constraint
{
    public $message = "La date que vous avez rentré ne peut-être inférieure à la date d'aujourd'hui.";

}