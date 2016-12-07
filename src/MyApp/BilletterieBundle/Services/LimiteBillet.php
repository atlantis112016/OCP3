<?php

/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 29/11/2016
 * Time: 10:41
 */
class limiteBillet
{
    // Renvoie true si 1000 billets ont été vendus à cette date
    public function isMilleBillets($listReserv, $quantity)
    {
        $totalBillet = 0;
        foreach ($listReserv as $command) {
            $billetQuantite = $command->getQuantity();
            $totalBillet += $billetQuantite;
        }

        return (($totalBillet + $quantity) > 1000 );
    }
}