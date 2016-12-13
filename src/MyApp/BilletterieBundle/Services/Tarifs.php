<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 30/11/2016
 * Time: 10:55
 */

namespace MyApp\BilletterieBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

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
    public function calculTarifs($id)
    {
        //---------------Recherche de la commande dans billet--------------------//
        $listeBillet = $this->em->getRepository('MyAppBilletterieBundle:Billet')
            ->findBy((array('commande' => $id)));

        //--------------------- Date du jour ---------------------//
        $dateNow = new \DateTime('now');

        //----------------Boucle sur les billets------------------//
       foreach ( $listeBillet as $billet) {

           //-------------Conversion date de naissance en âge---------//
           $dateNo = $billet->getDateNaissance();
           $age = $dateNo->diff($dateNow)->format('%y');

           //-------------Récupération de l'état de Tarif réduit----------//
           $tReduit = $billet->getTarifReduit();

           //----------------Condition pour trouver le bon montant et le bon type de tarif--------------//
           If ($tReduit) {
               $nomType = 'reduit';
           } else {
               switch ($age) {
                   case ($age >= 60):
                       $nomType = 'Senior';
                       break;
                   case ($age > 12 && $age < 60):
                       $nomType = 'normal';
                       break;
                   case ($age >= 4 && $age <= 11):
                       $nomType = 'enfant';
                       break;
                   case ($age < 4):
                       $nomType = 'gratuit';
                       break;
               }
           }

           //------------------- Récupération du montant par rapport au nom du type -------------//
           $listTarif = $this->em->getRepository('MyAppBilletterieBundle:TypeTarif')
               ->findOneBy((array('nomType' => $nomType)));

           //--------------------- Mise à jour du champ Montant dans Billet ---------------------//
           $billet->setMontant($listTarif->getMontant());
           $this->em->flush();
       }
    }

}