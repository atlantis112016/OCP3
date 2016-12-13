<?php
/**
 * Created by PhpStorm.
 * User: Fabienne Bernard
 * Date: 09/12/2016
 * Time: 14:22
 */

namespace MyApp\BilletterieBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

class TotalCde
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function totalCde($id)
    {

        $montantTotalCde = $this->em->getRepository('MyAppBilletterieBundle:Billet')
            ->sumMontant($id);
        $cde = $this->em->getRepository('MyAppBilletterieBundle:Commande')
            ->findOneBy(array('id' => $id));

        $cde->setMontantTotal($montantTotalCde);
        $this->em->flush();

    }
}