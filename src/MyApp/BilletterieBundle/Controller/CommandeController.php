<?php

namespace MyApp\BilletterieBundle\Controller;

use MyApp\BilletterieBundle\Entity\Billet;
use MyApp\BilletterieBundle\Entity\Commande;
use MyApp\BilletterieBundle\Form\CommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CommandeController extends Controller
{
    /**
     * @Route("/cde", name="my_app_billetterie_cde")
     */
    public function formAction(Request $request)
    {
        //connexion base
        $em = $this->getDoctrine()->getManager();
        //connexion à l'entité Commande
        $cde = new Commande();

        //permet de voir ce qui se passe dans symfony
        dump($cde);

        $form = $this->createForm(CommandeType:: class, $cde);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // ---------- GENERATION DU CODE DE RESERVATION ----------

                $getCodeReserv = $this->container->get('my_app_billetterie.coderesa'); /*utilisation du service*/

                $codeReserv = $getCodeReserv->genCodeResa();
                $cde->setCodeReserv($codeReserv);


                $cde = $form->getData();
                $em->persist($cde);


                //$em = $this->getDoctrine()->getManager();
                //$em->persist($cde);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'ETAPE 1 bien enregistrée');
                return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $cde->getId(),'CodeResa'=>$codeReserv));
            }

        }

        return $this->render('MyAppBilletterieBundle:billetterie:etape1.html.twig', array(
            'form' => $form->createView(),));
    }
}