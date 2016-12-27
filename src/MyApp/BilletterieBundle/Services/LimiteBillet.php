<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 09/12/2016
 * Time: 09:53
 */

namespace MyApp\BilletterieBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use MyApp\BilletterieBundle\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;

class LimiteBillet
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function isLimiteBillet(\DateTime $dateVisite, $nbBillet)
    {
        //--------- Récupération du seuil max ------//
        $maxi = Commande::MAX_BILLETS;

        //------ Liste les billets déjà enregistrés à la date du jour ---------//
        $listReserv = $this->em->getRepository('MyAppBilletterieBundle:Commande')
            ->findBy(array('dateVisite' => $dateVisite));

        $totalBillet=0;

        foreach ($listReserv as $command) {

            $billetQuantite = $command->getNbBillet();
            $totalBillet += $billetQuantite;

        }
            return (($totalBillet + $nbBillet) > $maxi);
    }
}