<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 30/11/2016
 * Time: 10:55
 */

namespace MyApp\BilletterieBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use MyApp\BilletterieBundle\Entity\Commande;

class Tarifs
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    // On injecte l'EntityManager
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function calculTarifs(Commande $actuCde)
    {
        //---------------Recherche de la commande dans billet--------------------//
        $listeBillet = $actuCde->getBillets();

        //--------------------- Date du jour ---------------------//
        $dateNow = new \DateTime('now');
        $montantTotal = 0;
        //----------------Boucle sur les billets------------------//
       foreach ( $listeBillet as $billet) {

           //-------------Conversion date de naissance en âge---------//
           $dateNo = $billet->getDateNaissance();
           $age = $dateNo->diff($dateNow)->format('%y');

           //----------------Condition pour trouver le bon montant et le bon type de tarif--------------//
           if ($billet->getTarifReduit() && $age > 11) {
               $billet->setTypeTarif('reduit');
               if ($actuCde->getTypeJournee() === 'Demi-journee') {
                   $billet->setMontant(10/2);
               } else {
                       $billet->setMontant(10);
               }
           } else {
               switch ($age) {
                   case ($age >= 60):
                       $billet->setTypeTarif('Senior');
                       if ($actuCde->getTypeJournee() === 'Demi-journee') {
                           $billet->setMontant(12/2);
                       } else {
                           $billet->setMontant(12);
                       }
                       break;
                   case ($age > 12 && $age < 60):
                       $billet->setTypeTarif('normal');
                       if ($actuCde->getTypeJournee() === 'Demi-journee') {
                           $billet->setMontant(16/2);
                       } else {
                           $billet->setMontant(16);
                       }
                       break;
                   case ($age >= 4 && $age <= 11):
                       $billet->setTypeTarif('enfant');
                       if ($actuCde->getTypeJournee() === 'Demi-journee') {
                           $billet->setMontant(8/2);
                       } else {
                           $billet->setMontant(8);
                       }
                       break;
               }
               if ($age < 4){
                   $billet->setTypeTarif('gratuit');

                   $billet->setMontant(0);
               }
           }

           $montantTotal += $billet->getMontant();
       }
        $actuCde->setMontantTotal($montantTotal);
        $this->em->flush();
    }

}