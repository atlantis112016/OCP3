<?php

namespace MyApp\BilletterieBundle\Command;

class GenCodeResa
{
    // Génère le numéro de commande
    public function genCodeResa()
    {
        $randomNumber = rand(1000, 5000);

        $letters = [ 'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $i = rand(0, 25);
        $letter = $letters[$i];

        $time = time();
        $codeReserv = 'LOUVRE' .  $randomNumber  . $letter . $time;

        return $codeReserv;
    }
}