<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 07/12/2016
 * Time: 14:23
 */

namespace MyApp\BilletterieBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DateAnniv extends Constraint
{
    public $message = "La date que vous avez rentré ne peut-être égal ou supérieur à la date d'aujourd'hui.";
}
