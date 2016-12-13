<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 09/12/2016
 * Time: 16:27
 */

namespace MyApp\BilletterieBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class JoursInterdit extends Constraint
{
    public $message = "Le Mardi et le Dimanche, le musée est fermé. Merci de choisir une autre date.";
    public $message2 = "La date sélectionnée est un jour férié, merci de choisir une autre date.";
}