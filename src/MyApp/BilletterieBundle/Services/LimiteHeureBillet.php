<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 08/12/2016
 * Time: 09:45
 */

namespace MyApp\BilletterieBundle\Services;


class LimiteHeureBillet
{
    // Renvoie true si le ticket est acheté après 14h le jour même
    public function isLimiteHeure(\DateTime $dateVisite)
    {
        $dateReserv = new \DateTime('now');
        $date1 = $dateReserv->format('d/m/Y');
        $date2 = $dateVisite->format('d/m/Y');
        $heureReserv = $dateReserv->format('H');

        if ($date1 == $date2) {
           // if ($heureReserv >= '14') {
                return ($heureReserv >= '14');
           // }
        }
    }
}
