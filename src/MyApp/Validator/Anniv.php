<?php
/**
 * Created by PhpStorm.
 * User: Fabienne Bernard
 * Date: 07/12/2016
 * Time: 09:45
 */
namespace MyApp\BilletterieBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class Anniv extends Constraint
{
    public $message = "Votre date de naissance %string%, doit-être inférieur à la date du jour";
}