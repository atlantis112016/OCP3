<?php

namespace MyApp\BilletterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * @Route("/test", name="my_app_billetterie_test")
     */
    public function codeAction()
    {
        $getCodeReserv = $this->container->get('my_app_billetterie.coderesa');
        $codeReserv = $getCodeReserv->genCodeResa();
        //return $this->render('', array('name' => $name));
        return new Response('Le code de réservation est : <br/>'.$codeReserv);
    }
    public function limiteBilletAction()
    {
        $cde = new Commande();
        $form = $this->createForm(new CommandeType(), $cde);
        $em = $this->getDoctrine()->getManager();
        $getMilleBillets = $this->container->get('my_app_billetterie.limitebillet');
        $getDatereserv = $form->get('datereserv')->getData();
        $getNbBillet = $form->get('nbBillet')->getData();

        $listReserv = $em->getRepository('my_app_billeterie.limiteBillet')->findBy(array('datereserv' => $getDatereserv));

        if ($getMilleBillets->isMilleBillets($listReserv, $getNbBillet) === true) {
            $this->get('session')->getFlashBag()->add('info', "Désolé, mais il n'y a plus de place à cette date!");
            return $this->redirectToRoute('my_app_billetterie_cde');
        }
    }

    /**
     * @Route("/test2", name="my_app_billetterie_test2")
     */
    function tarifAction ()
    {

        $date1 = new \DateTime('now');
        $date2 = $date1->format('d/m/Y H:i:s');

        $dateAnniv = new \DateTime('29-11-1948');
        $dateAnniv2 = $dateAnniv->format('d/m/Y');

        $interval = $date1->diff($dateAnniv);
        $monAge = $interval->format('%y ans');
        $tarif = 0;
        $tarifType = "";
        $tReduit = "false";

        switch ($tReduit)
        {
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

        return new Response('Mon âge est de  : <br/>'.$monAge.' <br/>le tarif est de : '.$tarif."<br/> et c'est un tarif: ".$tarifType.
            "<br/> et la date d'aujourd'hui est: ".$date2."<br/> et votre date de naissance est: ".$dateAnniv2);

    }
}
