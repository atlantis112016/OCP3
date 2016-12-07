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
    public $message = "La date %string% que vous avez rentré ne peut-être égal ou inférieur à la date d'aujourd'hui.";
}