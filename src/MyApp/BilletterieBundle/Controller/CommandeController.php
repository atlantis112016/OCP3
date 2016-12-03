<?php

namespace MyApp\BilletterieBundle\Controller;

use MyApp\BilletterieBundle\Entity\Billet;
use MyApp\BilletterieBundle\Entity\Commande;
use MyApp\BilletterieBundle\Form\CommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class CommandeController extends Controller
{
    /**
     * @Route("/cde/etape1/{id}", name="my_app_billetterie_cde", requirements={"id" = "\d+"}, defaults={"id" = 0})
     *
     */
    public function cdeAction(Request $request, $id)
    {
        //connexion base
        $em = $this->getDoctrine()->getManager();
        //connexion à l'entité Commande
        $cdes = $em->getRepository('MyAppBilletterieBundle:Commande')
            ->findOneBy(array('id' => $id));

        if (null === $cdes) {
            $cde = new Commande();
        }

        $form = $this->createForm(CommandeType:: class, $cdes);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $getDateVisite = $form->get('dateVisite')->getData();
                $getEmail = $form->get('email')->getData();
                $getNbreBillet = $form->get('nbBillet')->getData();
                $getTypeBillet = $form->get('typeJournee')->getData();

                $dateNow = new \DateTime();

                //permet de voir ce qui se passe dans symfony
                //dump($cde);

                $listCdes = $em->getRepository('MyAppBilletterieBundle:Commande')->findBy(array('dateVisite' => $getDateVisite));

                // ---------- GENERATION DU CODE DE RESERVATION ----------
                $getCodeReserv = $this->container->get('my_app_billetterie.coderesa'); /*utilisation du service*/
                $codeReserv = $getCodeReserv->genCodeResa();
                $cde->setCodeReserv($codeReserv);
                $cde ->setStatut('En cours');
                $cde->setTokenStripe('00000000000000');
                $em = $this->getDoctrine()->getManager();
                $em->persist($cde);

                // ---------- CREATION ET STOCKAGE DES BILLETS DANS L'ARRYCOLLECTION ----------
                for ($i =  0; $i < $getNbreBillet; $i++) {
                    $billet = new Billet();
                    $billet->setCommande($cde);
                    $em->persist($billet);
                    $cde->addBillet($billet);
                }

                $em->flush();
            }
            $request->getSession()->getFlashBag()->add('notice', 'ETAPE 1 bien enregistrée');
            return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $cde->getId(),));

        }
        return $this->render('MyAppBilletterieBundle:billetterie:etape1.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/cde/etape2/{id}", name="my_app_billetterie_billet", requirements={"id" = "\d+"})
     */
    public function billetAction(Request $request, $id)
    {
        //connexion base
        $em = $this->getDoctrine()->getManager();
        //connexion à l'entité Commande
        //$cde = new Commande();
        $actuCommande = $em->getRepository('MyAppBilletterieBundle:Commande')
            ->findOneBy(array('id' => $id));

        if (null === $actuCommande) {
            throw new Exception("Cette commande n'existe pas !");
        }

        $form = $this->get('form.factory')->create(CommandeType::class, $actuCommande);

        //permet de voir ce qui se passe dans symfony
        //dump($actuCommande);

        //$getDateVisite = $form->get('dateVisite')->getData();
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($actuCommande);
                $em->flush();
            }
            $request->getSession()->getFlashBag()->add('notice', 'ETAPE 2 bien enregistrée');
            return $this->redirectToRoute('my_app_billetterie_recap', array('id' => $actuCommande->getId()));

        }
        return $this->render('MyAppBilletterieBundle:billetterie:etape2.html.twig', array(
            'id' => $actuCommande->getId(),
            'dateVisite' => $actuCommande->getDateVisite(),
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/cde/etape3/{id}", name="my_app_billetterie_recap", requirements={"id" = "\d+"})
     */
    public function recapAction(Request $request, $id )
    {
       $em = $this->getDoctrine()->getManager();
       $ActuCde = $em->getRepository('MyAppBilletterieBundle:Commande')
            ->findOneBy(array('id' => $id));

        if (null === $ActuCde) {
            throw new Exception("Cette commande n'existe pas !");
        }
           $listeBillets = $em->getRepository('MyAppBilletterieBundle:Billet')
            ->findBy(array('commande' => $ActuCde));

        if (null === $listeBillets) {
            throw new Exception("Il n'y a pas de billets !");
        }

            $listeTypes = $em->getRepository('MyAppBilletterieBundle:TypeTarif')
            -> findBy(array('id' => $ActuCde->getTypeJournee())
            );

        if (null === $listeTypes) {
            throw new Exception("Pas de type correspondant");
        }

           return $this->render('MyAppBilletterieBundle:billetterie:etape3.html.twig', array(
            'recapCde' => $ActuCde,
            'recapBillets' => $listeBillets,
            'listeType' => $listeTypes,
            'id' => $ActuCde->getId()
           ));
    }

    /**
     * @Route("/cde/etape4/{id}", name="my_app_billetterie_paiement", requirements={"id" = "\d+"})
     */
    public function paiementAction(Request $request, $id)
    {

        return $this->render('MyAppBilletterieBundle:billetterie:etape4.html.twig', array(
            'form' => $form->createView(),));
    }
}