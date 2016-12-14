<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 13/12/2016
 * Time: 20:38
 */

namespace MyApp\BilletterieBundle\Validator;

use Symfony\Component\Validator\Constraint;
/**
 * @Annotation
 */
class LimiteBillet extends Constraint
{
    public $message = "Il n'y a plus de place à cette date là!.";

    public function validatedBy()
    {
        return 'my_app_billetterie.limitebillet'; // Ici, on fait appel à l'alias du service
    }
}

