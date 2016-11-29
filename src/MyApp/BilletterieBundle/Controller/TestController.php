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
}
