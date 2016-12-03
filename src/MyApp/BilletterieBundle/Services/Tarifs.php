<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 30/11/2016
 * Time: 10:55
 */

namespace MyApp\BilletterieBundle\Services;

use Doctrine\ORM\EntityManager;

class Tarifs
{
    public function Calcul($billet, $age, $nombreEnfant, $nombreAdulte)
    {

        switch ($billet) {

            case ($billet->getTarifReduit() === true):
                $billet->setPrix('10');
                $billet->setTarif('Reduit');
                break;

            case ($age >= 60):
                $billet->setPrix('12');
                $billet->setTarif('Senior');
                break;

            case ($age > 12 && $age < 60):
                $billet->setPrix('16');
                $billet->setTarif('Normal');
                $nombreAdulte++;
                break;

            case ($age >= 4 && $age <= 12):
                $billet->setPrix('8');
                $billet->setTarif('Enfant');
                $nombreEnfant++;
                break;

            case ($age < 4):
                $billet->setPrix('0');
                $billet->setTarif('Jeune enfant');
                break;

        }
    }

}