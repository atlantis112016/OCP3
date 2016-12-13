<?php

namespace MyApp\BilletterieBundle\Controller;

use MyApp\BilletterieBundle\Entity\Billet;
use MyApp\BilletterieBundle\Entity\Commande;
use MyApp\BilletterieBundle\Form\CommandeEditType;
use MyApp\BilletterieBundle\Form\CommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class TestController extends Controller
{
    /**
     * @Route("/test", name="my_app_billetterie_test")
     */

    public function limiteBilletAction()
    {
        /* $cde = new Commande();
         $form = $this->createForm(new CommandeType(), $cde);
         $em = $this->getDoctrine()->getManager();
         $getMilleBillets = $this->container->get('my_app_billetterie.limitebillet');
         $getDatereserv = $form->get('datereserv')->getData();
         $getNbBillet = $form->get('nbBillet')->getData();

         $listReserv = $em->getRepository('my_app_billeterie.limiteBillet')->findBy(array('datereserv' => $getDatereserv));

         if ($getMilleBillets->limitBillet($listReserv, $getNbBillet) === true) {
             $this->get('session')->getFlashBag()->add('info', "Désolé, mais il n'y a plus de place à cette date!");
          }*/
    }

    /**
     * @Route("/test2", name="my_app_billetterie_test2")
     */
    function tarifAction()
    {

        $date1 = new \DateTime('now');
        $date2 = $date1->format('d/m/Y H:i:s');
        $heureNow = $date1->format('H:i');
        $dateAnniv = new \DateTime('29-11-1948');
        $dateAnniv2 = $dateAnniv->format('d/m/Y');

        $interval = $date1->diff($dateAnniv);
        $monAge = $interval->format('%y ans');
        $tarif = 0;
        $tarifType = "";
        $tReduit = "false";
        $dateNow = new \DateTime('now');
        $value = new \DateTime('07-12-2016');
        $toto = 'false';
        if ($dateNow->format('d/m/Y') <= $value->format('d/m/Y')) {
            $toto = 'true';
        }


        switch ($tReduit) {
            case ($tReduit === "true") :
                $tarif = 10;
                $tarifType = "Réduit";
                break;
            case ($monAge >= 60) :
                $tarif = 12;
                $tarifType = "Senior";
                break;
            case ($monAge >= 12 && $monAge < 60) :
                $tarif = 16;
                $tarifType = "Normal";
                break;
            case ($monAge >= 4 && $monAge <= 11) :
                $tarif = 8;
                $tarifType = "Enfant";
                break;
            case ($monAge < 4) :
                $tarif = 0;
                $tarifType = "Bébé";
                break;
        }

        return new Response('Mon âge est de  : <br/>' . $monAge . ' <br/>le tarif est de : ' . $tarif . "<br/> et c'est un tarif: " . $tarifType .
            "<br/> et la date d'aujourd'hui est: " . $date2 . "<br/> et votre date de naissance est: " . $dateAnniv2 . " <br /> TOTO =" . $toto .
            '<br/>Heure de maintenant : ' . $heureNow);

    }

    /**
     * @Route("/test3/{id}", name="my_app_billetterie_test3")
     */
    function validatorAction($id)
    {
        $cde = new Commande();
        //$thedate = new \DateTime('now');
        $thedate = new \DateTime('14-12-2016 12:00:00');
        $cde->setDatereserv(new \Datetime());
        $cde->setEmail('abc');
        $cde->setDateVisite($thedate);

        $billet = new Billet();
        $billet->setDateNaissance($thedate);

        //------------- On récupère le service validator ------------------//
        $validator = $this->get('validator');

        //-------------------- test de vérification si 14h et même jour  ----------------------------//
        $getLimiteHeure = $this->container->get('my_app_billetterie.heure');
        //$dateReserv = new \DateTime('08-12-2016 15:00:00');
        //$dateVisite = new \DateTime('08-12-2016');
        $getTypeJournee = 'Journée';

        If ($getTypeJournee === 'Journée' && $getLimiteHeure->isLimiteHeure($thedate, $thedate) === true) {
            return new Response("vous devez prendre un ticket demi-journée");
        }

        //----------------- On déclenche la validation sur notre object --------------//
        $listErrors = $validator->validate($cde);

        //----------------- Si $listErrors n'est pas vide, on affiche les erreurs --------//
        if (count($listErrors) > 0) {
            // $listErrors est un objet, sa méthode __toString permet de lister joliement les erreurs
            return new Response('<br />' . $listErrors);
        } else {
            return new Response("La commande est valide !");
        }

    }

    /**
     * @Route("/test4", name="my_app_billetterie_test4")
     */
    function validatorBilletAction()
    {
        $billet = new Billet();
        $thedate = new \DateTime('14-12-2016');
        $billet->setDateNaissance($thedate);

        // On récupère le service validator
        $validator = $this->get('validator');

        // On déclenche la validation sur notre object
        $listErrors = $validator->validate($billet);

        // Si $listErrors n'est pas vide, on affiche les erreurs
        if (count($listErrors) > 0) {
            // $listErrors est un objet, sa méthode __toString permet de lister joliement les erreurs
            return new Response('' . $listErrors);
        } else {
            return new Response("Le billet est valide !");
        }
    }

    /**
     * @Route("/test5", name="my_app_billetterie_test5")
     * test de vérification 14h jour reserv et date visite identique
     */
    function LimeHeureBilletAction()
    {
        $getLimiteHeure = $this->container->get('my_app_billetterie.heure');
        $dateReserv = new \DateTime('08-12-2016 15:00:00');
        $dateVisite = new \DateTime('08-12-2016');
        $getTypeJournee = 'Journée';

        If ($getTypeJournee === 'Journée' && $getLimiteHeure->isLimiteHeure($dateReserv, $dateVisite) === true) {
            return new Response("vous devez prendre un ticket demi-journée");
        }
        return new Response("Tout va bien");
    }

    /**
     * @Route("/test6/{id}", name="my_app_billetterie_test6")
     * calcul des tarifs à l'aide du service + update dans entité billet
     */
    function tarifsAgeAction($id)
    {
        // Services
        $calculTarif = $this->container->get('my_app_billetterie.tarifs');
        // Mise à jours des tarifs des billets
        $calculTarif->calculTarifs($id);

        return new Response ('Mise à jours des montants effectués!');
    }

    /**
     * @Route("/test7/{id}", name="my_app_billetterie_test7")
     * limite billet 1000 en utilisant le service limitebillet
     * calcul du montant total de la commande et update entité cde à l'aide du service totalcde
     **/
    function paramConverterAction($id)
    {
        //limite nbre billet
        $getlimiteBillet = $this->container->get('my_app_billetterie.limitebillet');
        $maxi = Commande::MAX_BILLETS;
        $dateVisite = new \DateTime('2016-12-29');

        if (($getlimiteBillet->isLimiteBillet($dateVisite, $maxi) === true)) {
            throw $this->createNotFoundException(
                'La limite de vente de billet pour le ' . $dateVisite->format('d/m/Y') . ' est dépassée. Merci de choisir une autre date '
            );
        }
        //------------------------- Mettre le montant total dans commande -----------------//
        $totalCde = $this->container->get('my_app_billetterie.totalcde');
        $totalCde->totalCde($id);

//dump($montantTotalCde);
//die();
        return new Response('Le montant total de votre Commande a bien été enregistrée ');
    }

    /**
     * @Route("/test8", name="my_app_billetterie_test8")
     * Paiement Stripe
     *
     **/
    function paiementStripe()
    {
        $dateVisite = new \DateTime('18-08-2016');
        $dateVisite2 = $dateVisite->format('d-m-Y');
        $dateVisite3 = $dateVisite->format('d/m');
        $tabJrsFeries = array('01/01','17/04','01/05','08/05','25/05','14/07','15/08','01/11','25/12');
        $jrsTrouve = 'false';
        foreach ($tabJrsFeries as &$jrsferie)
        {
            if ($jrsferie == $dateVisite3){
                $jrsTrouve = 'true';
            }
        }
        return new Response($jrsTrouve);
        //---------- Numéro du jour de la semaine
        $jrSem = date('w', strtotime($dateVisite2));

        if ($jrSem == 0 or $jrSem == 2)
        {
            return new Response('perdu!!');
        }
       // $dateInterval = $dateVisite ->diff($dateJour)->format('%a');
        return new Response($dateVisite2.' + ' .$jrSem);
    }

    /**
     * @Route("/test9/{id}", name="my_app_billetterie_test9")
     * Paiement Stripe
     *
     **/
    function testMail($id)
    {
        $em = $this->getDoctrine()->getManager();
        //---------------- Récupération de la commande ----------------//
        $recapCde = $em->getRepository('MyAppBilletterieBundle:Commande')
            ->findOneBy(array('id' => $id));

        //------------------- Listing des billets par rapport à la commande ------------//
        $recapBillets = $em->getRepository('MyAppBilletterieBundle:Billet')
            ->findBy(array('commande' => $recapCde));

        $message = \Swift_Message::newInstance();
        $imgUrl = $message->embed(\Swift_Image::fromPath('bundles/myappbilletterie/images/logo50.jpg'));
        $message->setSubject("Confirmation de votre commande")
                 ->setFrom('serviceClient@museedulouvre.com')
                 ->setTo('atlantis11@libertysurf.fr')
                 ->setCharset('utf-8')
                 ->setContentType('text/html')
                 ->setBody($this->renderView('@MyAppBilletterie/billetterie/email.html.twig',
            array('recapCde' => $recapCde,'recapBillets' => $recapBillets, 'url'=>$imgUrl),'text/html'));

        //envoi du message
       // $this->get('mailer')->send($message);
        $this->addFlash('success', 'Paiement accepté, vous allez recevoir vos billets par Email !');
 //       return $this->redirectToRoute('my_app_billetterie_test9', array('id'=>$id));

        return $this->render('MyAppBilletterieBundle:billetterie:test2.html.twig', array(
            'id' => $id,));


//        foreach ($this->getFlashBag()->all() as $type => $msgs) {
  //          foreach ($msgs as $msg) {
    //            echo '<div class="flash-'.$type.'">'.$msg.'</div>';
      //      }
        //}
        //return new Response('mail envoyé');
    }
       /* $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation de votre commande')
            ->setFrom('serviceClient@museedulouvre.com')
            ->setTo('atlantis11@libertysurf.fr')
            ->setReplyTo('xxx@xxx.xxx')
            ->setBody('Test de la Billetterie du louvre!');

        $this->get('mailer')->send($message);
    }*/
}
