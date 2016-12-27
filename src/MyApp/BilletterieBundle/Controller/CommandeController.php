<?php

namespace MyApp\BilletterieBundle\Controller;

use MyApp\BilletterieBundle\Entity\Billet;
use MyApp\BilletterieBundle\Entity\Commande;
use MyApp\BilletterieBundle\Form\CommandeCollectionBilletType;
use MyApp\BilletterieBundle\Form\CommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Stripe\Stripe;
use Stripe\Charge;



class CommandeController extends Controller
{
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //------------------- ETAPE 1 :Génération de la commande et des billets avec vérif des contraintes ---------------//
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/cde/etape1", name="my_app_billetterie_cde")
     *
     */

    public function cdeAction(Request $request)
    {
        $cde = new Commande();
        $form = $this->createForm(CommandeType:: class, $cde);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                //---------------------- Test Limite Billet - utilisation du service ------------------//
                $isLimiteBillet =  $this->container->get('my_app_billetterie.limitebillet');

                 if (($isLimiteBillet->isLimiteBillet($cde->getDateVisite(), $cde->getNbBillet()) === true)) {

                $this->addFlash('danger',
                    'La limite de vente de billet pour le ' . $cde->getDateVisite()->format('d/m/Y') . ' est dépassée. Merci de choisir une autre date '
                );
                    return $this->redirectToRoute('my_app_billetterie_cde');
                }

                //------------------------------------- Test Heure 14h -------------------------------//
                $isHeureBillet =  $this->container->get('my_app_billetterie.heure');
                If ($cde->getTypeJournee() === 'Journee' && $isHeureBillet->isLimiteHeure($cde->getDateVisite()) === true) {
                    $this->addFlash('danger', 'Vous devez prendre un ticket demi-journée');
                    return $this->redirectToRoute('my_app_billetterie_cde');
                }

                //----------- Chgt du statut de la commande --------------
                $cde ->setStatut(Commande::STATUT_ENCOURS);

                $em = $this->getDoctrine()->getManager();
                $em->persist($cde);

                // ---------- CREATION ET STOCKAGE DES BILLETS DANS L'ARRYCOLLECTION ----------
                for ($i =  0; $i < $cde->getNbBillet(); $i++) {
                    $cde->addBillet(new Billet());
                }
                $em->flush();

            $this->addFlash('Info', 'ETAPE 1 bien enregistrée');
            return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $cde->getId(),));
            }

        return $this->render('MyAppBilletterieBundle:CommandeController:etape1.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //--------------------- ETAPE 1 :Mise à jour de la Commande quand le visisteur clique sur précédent --------------//
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/cde/etape1/{id}", name="my_app_billetterie_editcde", requirements={"id" = "\d+"})
     **/
    public function editCdeAction(Request $request, Commande $actuCommande)
    {
        //-------------------- Nbre de Billet avant le formulaire ------------------->
        $nbreBillet = $actuCommande->getNbBillet();

        $form = $this->get('form.factory')->create(CommandeType::class, $actuCommande);
        $form -> handleRequest(($request));

        if ($form->isSubmitted() && $form->isValid()){
            //-------- Calcul la différence entre la Bdd actuelle et la form ------------------//
            $limitBillet = $actuCommande->getNbBillet() - $nbreBillet;
            if ($limitBillet < 0){
                $this->addFlash('danger', 'Le nombre de billet doit être supérieur à votre choix précédent');
                return $this->redirectToRoute('my_app_billetterie_editcde', array('id' => $actuCommande->getId(),));
            }

            //---------------------- Test Limite Billet - utilisation du service ------------------//
            $isLimiteBillet =  $this->container->get('my_app_billetterie.limitebillet');
            if (($isLimiteBillet->isLimiteBillet($actuCommande->getDateVisite(), 0) === true)) {
                $this->addFlash('danger',
                    'La limite de vente de billet pour le ' . $actuCommande->getDateVisite()->format('d/m/Y') . ' est dépassée. Merci de choisir une autre date '
                );
                return $this->redirectToRoute('my_app_billetterie_editcde', array('id' => $actuCommande->getId(),));
            }

            //------------------------------------- Test Heure 14h -------------------------------//
            $isHeureBillet =  $this->container->get('my_app_billetterie.heure');
            If ($actuCommande->getTypeJournee() === 'Journee' && $isHeureBillet->isLimiteHeure($actuCommande->getDateVisite()) === true) {
                return new Response("vous devez prendre un ticket demi-journée");
            }

            // ---------- CREATION ET STOCKAGE DES BILLETS DANS L'ARRYCOLLECTION ---------//
            for ($i = 0; $i < $limitBillet; $i++) {
                $actuCommande->addBillet(new Billet());
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('info', 'ETAPE 1 bien modifié');
            return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $actuCommande->getId(),));

        }
        return $this->render('MyAppBilletterieBundle:CommandeController:etape1.html.twig', array(
            'form' => $form->createView(),));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //--------------------------------------- ETAPE 2 :Mise à jour des billets ---------------------------------------//
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/cde/etape2/{id}", name="my_app_billetterie_billet", requirements={"id" = "\d+"})
     *
     */
    public function billetAction(Request $request, Commande $actuCommande)
    {
        $listBillet = $actuCommande->getBillets();

        $form = $this->get('form.factory')->create(CommandeCollectionBilletType::class, $actuCommande);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
               // $em->persist($actuCommande);
                $em->flush();

            $this->addFlash('info', 'ETAPE 2 bien enregistrée');

            return $this->redirectToRoute('my_app_billetterie_recap', array('id' => $actuCommande->getId()));
            }
        }
        return $this->render('MyAppBilletterieBundle:CommandeController:etape2.html.twig', array(
            'id' => $actuCommande->getId(),
            'dateVisite' => $actuCommande->getDateVisite(),
            'form' => $form->createView(),
            'listeBillets' => $listBillet,
        ));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //------------------------------ ETAPE 2 :Ajout d'un billet par le bouton "Ajouter" ------------------------------//
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/cde/etape2/addBillet/{id}", name="my_app_billetterie_addbillet", requirements={"id" = "\d+"})
     **/
    public function addBillet(Request $request, Commande $cde)
    {
        $getNbBillet = $cde->getNbBillet();
       // dump($nbBillet);die();
        //---------------------- Test Limite Billet - utilisation du service ------------------//
        $isLimiteBillet =  $this->container->get('my_app_billetterie.limitebillet');
        if (($isLimiteBillet->isLimiteBillet($cde->getDateVisite(), 1) === true)) {
            $this->addFlash('danger',
                'La limite de vente de billet pour le ' . $cde->getDateVisite()->format('d/m/Y') .
                ' est dépassée. Vous ne pouvez plus ajouter de billet, merci de choisir une autre date.'
            );
            return $this->redirectToRoute('my_app_billetterie_editcde', array('id' => $cde->getId(),));
        }
        // ---------- CREATION ET STOCKAGE DES BILLETS DANS L'ARRYCOLLECTION ----------
        for ($i = 0; $i < 1; $i++) {
            $cde->addBillet(new Billet());
        }
        $cde->setNbBillet($getNbBillet + 1);
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('info', 'Nouveau Billet enregistré');
        return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $cde->getId(),));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //------------------------------ ETAPE 2 :Suppression d'un billet par le bouton "X" ------------------------------//
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/cde/etape2/deleteBillet/{id}", name="my_app_billetterie_deletebillet", requirements={"id" = "\d+"})
     **/
    public function deleteBillet(Request $request, Billet $billet)
    {
        $em = $this->getDoctrine()->getManager();

        //-----------Requête pour cibler le billet que l'on souhaite supprimer---------//
        // $deleteCde = $em->getRepository('MyAppBilletterieBundle:Billet')
        //            ->findOneBy(array('id'=>$id));

         //-----------Récupération du numéro de la cde---------//
         $numCde = $billet->getCommande()->getId();
         $recNbBillet = $em->getRepository('MyAppBilletterieBundle:Commande')
             ->findOneBy(array('id'=>$numCde));
         $nbBillet=$recNbBillet->getNbBillet();
            if ($nbBillet == 1){
                $this->addFlash('warning', "Vous ne pouvez pas supprimer l'unique billet existant");
                return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $numCde));
            }
         $recNbBillet->setNbBillet($nbBillet-1);
         //-----------Suppression du Billet--------------//
         $em->remove($billet);
         $em->flush();
                return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $numCde));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //--- ETAPE 3 : Génération de la Récapitulation de la commande et calcul des tarifs par billet et montant total---//
    // ------------------------------------------------de la commande ------------------------------------------------//
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/cde/etape3/{id}", name="my_app_billetterie_recap", requirements={"id" = "\d+"})
     * */
    public function recapAction(Request $request, Commande $actuCde )
    {

        //------------------- Listing des billets par rapport à la commande ------------//
          // $listeBillets = $actuCde->getBillets();

        //---------------- Calcul des tarifs des billets appel du service Tarifs.php ---------------//
        $calculTarif = $this->container->get('my_app_billetterie.tarifs');
        $calculTarif->calculTarifs($actuCde);

           return $this->render('MyAppBilletterieBundle:CommandeController:etape3.html.twig', array(
            'id' => $actuCde->getId(),
            'recapCde' => $actuCde,
            'stripe_public_key' => $this->getParameter("stripe_public_key"),
           ));
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //------------------------- ETAPE 4 : Paiement STRIPE et envoi par mail de la commande ---------------------------//
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @Route("/cde/etape4/{id}", name="my_app_billetterie_paiement", requirements={"id" = "\d+"})
     * PAIEMENT
     */
    public function paiementAction(Request $request, Commande $recapCde)
    {
            $em = $this->getDoctrine()->getManager();
            $secretKey = $this->getParameter('stripe_secret_key');
            $error = false ;
        //---------------------Si le formulaire de paiement est soumis ------------------------//
        if ($request->isMethod('POST')) {
                try {
                    //-------------- Appel du sevice Stripe ---------------->
                    $this->get('my_app_billetterie.stripe')->Token($recapCde->getId(), $secretKey);
                }
                catch (\Stripe\Error\Card $e) {
                    $error = 'Un problème est survenu lors du paiement : '.$e->getMessage()."Merci d'essayez de nouveau!";
                    $recapCde->setStatut(Commande::STATUT_AVORTE);
                    $em->flush();
                    $this->addFlash('danger',$error);
                    return $this->redirectToRoute('my_app_billetterie_recap', array('id'=>$recapCde->getId()));
                }
            //---------------------Si pas d'erreur on envoie le mail ------------------------//
            if (!$error) {
                $this->addFlash('info', 'Votre paiement a été accepté. 
           Vous allez recevoir un email récapitulatif de votre commande.');

           //--------------------- Appel au service d'envoi de mail -------------------------//
                $this->get('my_app_billetterie.sendmail')->confirmMail($recapCde);

           //--------------------- Mise à jour du statut de la commande --------------------//
                $recapCde->setStatut(Commande::STATUT_TERMINE);
                $em->flush();

           //--------------------- Retourne à la page d'accueil ----------------------------//
                return $this->redirectToRoute('my_app_billetterie_home');
            }
        }
       return $this->render('MyAppBilletterieBundle:CommandeController:etape3.html.twig', array(
       '$id' => $recapCde->getId(),));
    }
}