<?php

namespace MyApp\BilletterieBundle\Controller;

use MyApp\BilletterieBundle\Entity\Billet;
use MyApp\BilletterieBundle\Entity\Commande;
use MyApp\BilletterieBundle\Form\CommandeCollectionBilletType;
use MyApp\BilletterieBundle\Form\CommandeEditType;
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
    /**
     * @Route("/cde/etape1", name="my_app_billetterie_cde")
     * Génération de la commande avec vérif des contraintes
     */

    public function cdeAction(Request $request)
    {
        $cde = new Commande();
        $form = $this->createForm(CommandeType:: class, $cde);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $getDateVisite = $form->get('dateVisite')->getData();
                $getNbreBillet = $form->get('nbBillet')->getData();
                $getTypeBillet = $form->get('typeJournee')->getData();

                //permet de voir ce qui se passe dans symfony
                //dump($cde);
                //---------------------- Test Limite Billet - utilisation du service ------------------//
                $isLimiteBillet =  $this->container->get('my_app_billetterie.limitebillet');

                 if (($isLimiteBillet->isLimiteBillet($cde->getDateVisite(), $getNbreBillet) === true)) {
                    throw $this->createNotFoundException(
                        'La limite de vente de billet pour le ' . $cde->getDateVisite()->format('d/m/Y') . ' est dépassée. Merci de choisir une autre date '
                    );
                }

                //------------------------------------- Test Heure 14h -------------------------------//
                $isHeureBillet =  $this->container->get('my_app_billetterie.heure');
                If ($getTypeBillet === 'Journee' && $isHeureBillet->isLimiteHeure($getDateVisite) === true) {
                    return new Response("vous devez prendre un ticket demi-journée");
                }

                //----------- Chgt du statut de la commande --------------
                $cde ->setStatut(Commande::STATUT_ENCOURS);

                //----------- Mettre un token Fictif en attendant finalisation cde ---------------------
                $cde->setTokenStripe('00000000000000');
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

        return $this->render('MyAppBilletterieBundle:billetterie:etape1.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/cde/etape1/{id}", name="my_app_billetterie_editcde", requirements={"id" = "\d+"})
     * Mise à jour de l'étape1 quand le visisteur clique sur précédent
     */
    public function editCdeAction(Request $request, Commande $actuCommande)
    {
        $nbreBillet = $actuCommande->getNbBillet();

        $form = $this->get('form.factory')->create(CommandeEditType::class, $actuCommande);
        $form -> handleRequest(($request));

        if ($form->isSubmitted() && $form->isValid()){
            //$em = $this ->getDoctrine()->getManager();
            $formBillet = $form->get('nbBillet')->getData();
            $formDateVisite = $form->get('dateVisite')->getData();
            $formTypeBillet = $form->get('typeJournee')->getData();

            //-------- Calcul la différence entre la Bdd actuelle et la form ------------------//
            $limitBillet = $formBillet - $nbreBillet;
            if ($limitBillet < 0){
                $this->addFlash('notice', 'Le nombre de billet doit être supérieur à votre choix précédent');
                return $this->redirectToRoute('my_app_billetterie_editcde', array('id' => $actuCommande->getId(),));
            }

            //---------------------- Test Limite Billet - utilisation du service ------------------//
            $isLimiteBillet =  $this->container->get('my_app_billetterie.limitebillet');

            if (($isLimiteBillet->isLimiteBillet($formDateVisite, $formBillet) === true)) {
                throw $this->createNotFoundException(
                    'La limite de vente de billet pour le ' . $formDateVisite->format('d/m/Y') . ' est dépassée. Merci de choisir une autre date '
                );
            }

            //------------------------------------- Test Heure 14h -------------------------------//
            $isHeureBillet =  $this->container->get('my_app_billetterie.heure');
            If ($formTypeBillet === 'Journee' && $isHeureBillet->isLimiteHeure($formDateVisite) === true) {
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
        return $this->render('MyAppBilletterieBundle:billetterie:etape1.html.twig', array(
            'form' => $form->createView(),));
    }

    /**
     * @Route("/cde/etape2/{id}", name="my_app_billetterie_billet", requirements={"id" = "\d+"})
     * Mise à jour Billet
     */
    public function billetAction(Request $request, Commande $actuCommande)
    {
        $listBillet = $actuCommande->getBillets();

        $form = $this->get('form.factory')->create(CommandeCollectionBilletType::class, $actuCommande);
           // $form->remove('dateVisite');
           // $form->remove('typeJournee');
           // $form->remove('nbBillet');
           // $form->remove('email');

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
        return $this->render('MyAppBilletterieBundle:billetterie:etape2.html.twig', array(
            'id' => $actuCommande->getId(),
            'dateVisite' => $actuCommande->getDateVisite(),
            'form' => $form->createView(),
            'listeBillets' => $listBillet,
        ));
    }

    /**
     * @Route("/cde/etape2/addBillet/{id}", name="my_app_billetterie_addbillet", requirements={"id" = "\d+"})
     * Bouton ajouter un billet dans twig etape2
     */
    public function addBillet(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $cde = $em->getRepository('MyAppBilletterieBundle:Commande')
            ->findOneBy(array('id'=>$id));

        if (null === $cde) {
            throw new Exception("Cette commande n'existe pas");
        }
        $getNbBillet = $cde->getNbBillet();
        $nbBillet = $getNbBillet + 1;

        // ---------- CREATION ET STOCKAGE DES BILLETS DANS L'ARRYCOLLECTION ----------
        for ($i =  $getNbBillet; $i < $nbBillet; $i++) {
            $cde->addBillet(new Billet());
        }
        $cde->setNbBillet($nbBillet);
        $em->flush();

        $this->addFlash('info', 'Nouveau Billet enregistré');
        return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $id,));

        //return new Response('Nouveau Billet : ' .$nbBillet);
    }

    /**
     * @Route("/cde/etape2/deleteBillet/{id}", name="my_app_billetterie_deletebillet", requirements={"id" = "\d+"})
     * Suppression d'un billet dans twig étape2 à l'aide d'un tableau
     */
    public function deleteBillet(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        //-----------Requête pour cibler le billet que l'on souhaite supprimer---------//
         $deleteCde = $em->getRepository('MyAppBilletterieBundle:Billet')
                    ->findOneBy(array('id'=>$id));

         //-----------Récupération du numéro de la cde---------//
         $numCde = $deleteCde->getCommande()->getId();
         $recNbBillet = $em->getRepository('MyAppBilletterieBundle:Commande')
             ->findOneBy(array('id'=>$numCde));
         $nbBillet=$recNbBillet->getNbBillet();
            if ($nbBillet == 1){
                    throw new Exception("Vous ne pouvez pas supprimer l'unique billet existant");
                    return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $numCde));
            }
         $recNbBillet->setNbBillet($nbBillet-1);
         //-----------Suppression du Billet--------------//
         $em->remove($deleteCde);
         $em->flush();
                return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $numCde));
    }

    /**
     * @Route("/cde/etape3/{id}", name="my_app_billetterie_recap", requirements={"id" = "\d+"})
     * Récap de la commmande avant billet et mise à jour des tarifs par billet et calcul montant total
     */
    public function recapAction(Request $request, Commande $actuCde )
    {

        //------------------- Listing des billets par rapport à la commande ------------//
           $listeBillets = $actuCde->getBillets();

        //---------------- Calcul des tarifs des billets appel du service Tarifs.php ---------------//
        $calculTarif = $this->container->get('my_app_billetterie.tarifs');
        $calculTarif->calculTarifs($actuCde);

           return $this->render('MyAppBilletterieBundle:billetterie:etape3.html.twig', array(
            'id' => $actuCde->getId(),
            'recapCde' => $actuCde,
            'stripe_public_key' => $this->getParameter("stripe_public_key"),
           ));
    }

    /**
     * @Route("/cde/etape4/{id}", name="my_app_billetterie_paiement", requirements={"id" = "\d+"})
     * PAIEMENT
     */
    public function paiementAction(Request $request, $id)
    {
            $secretKey = $this->getParameter('stripe_secret_key');
            $error = false ;
        //$paiement = $this->container->get('my_app_billetterie.stripe');
            //$paiement->Token($id, $secretKey);
        //---------------------Si le formulaire de paiement est soumis ------------------------//
        if ($request->isMethod('POST')) {
                try {
                    //-------------- Appel du sevice Stripe ---------------->
                    $this->get('my_app_billetterie.stripe')->Token($id, $secretKey);
                }
                catch (\Stripe\Error\Card $e) {
                    $error = 'Un problème est survenu lors du paiement : '.$e->getMessage();
                }
            //---------------------Si pas d'erreur on envoie le mail ------------------------//
            if (!$error) {
                $this->addFlash('info', 'Votre paiement a été accepté. 
           Vous allez recevoir un email récapitulatif de votre commande.');

                return $this->redirectToRoute('my_app_billetterie_mail', array('id'=>$id));
            }
        }
       return $this->render('MyAppBilletterieBundle:billetterie:etape3.html.twig', array(
       '$id' => $id,));
    }

    /**
     * @Route("/cde/etape5/{id}", name="my_app_billetterie_mail", requirements={"id" = "\d+"})
     * PAIEMENT
     */
    public function sendMail($id)
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
         $this->get('mailer')->send($message);

        return $this->redirectToRoute('my_app_billetterie_home');
        //return $this->render('MyAppBilletterieBundle:billetterie:etape4.html.twig', array(
       // '$id' => $id,));
    }
}