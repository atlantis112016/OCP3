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



class CommandeController extends Controller
{
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
                $getDateVisite = $form->get('dateVisite')->getData();
              //  $getEmail = $form->get('email')->getData();
                $getNbreBillet = $form->get('nbBillet')->getData();
              //  $getTypeBillet = $form->get('typeJournee')->getData();
               // $dateNow = new \DateTime();
                //permet de voir ce qui se passe dans symfony
                //dump($cde);
                //$listCdes = $em->getRepository('MyAppBilletterieBundle:Commande')->findBy(array('dateVisite' => $getDateVisite));

                // ---------- GENERATION DU CODE DE RESERVATION ----------
                $getCodeReserv = $this->container->get('my_app_billetterie.coderesa'); /*utilisation du service*/
                $codeReserv = $getCodeReserv->genCodeResa();
                $cde->setCodeReserv($codeReserv);

                //----------- Chgt du statut de la commande --------------
                $cde ->setStatut('En cours');

                //----------- Mettre un token Fictif en attendant finalisation cde ---------------------
                $cde->setTokenStripe('00000000000000');
                $em = $this->getDoctrine()->getManager();
                $em->persist($cde);

                // ---------- CREATION ET STOCKAGE DES BILLETS DANS L'ARRYCOLLECTION ----------
                for ($i =  0; $i < $getNbreBillet; $i++) {
                  //  $billet = new Billet();
                  //  $billet->setCommande($cde);
                  //  $em->persist($billet);
                    $cde->addBillet(new Billet());
                }

                $em->flush();
            }
            $this->addFlash('notice', 'ETAPE 1 bien enregistrée');
            return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $cde->getId(),));


        return $this->render('MyAppBilletterieBundle:billetterie:etape1.html.twig', array(
            'form' => $form->createView(),
            ));
    }

    /**
     * @Route("/cde/etape1/{id}", name="my_app_billetterie_editcde", requirements={"id" = "\d+"})
     */
    public function editCdeAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $actuCommande = $em->getRepository('MyAppBilletterieBundle:Commande')
            ->findOneBy(array('id' => $id));
        $NbreBillet = $actuCommande->getNbBillet();
        if (null === $actuCommande) {
            throw new Exception("Cette commande n'existe pas !");
            return $this->redirectToRoute('my_app_billetterie_cde');
        }
        $form = $this->get('form.factory')->create(CommandeEditType::class, $actuCommande);
        $form -> handleRequest(($request));

        if ($form->isSubmitted() && $form->isValid()){
            //$em = $this ->getDoctrine()->getManager();
            $formBillet = $form->get('nbBillet')->getData();
            //-------- Calcul la différence entre la Bdd actuelle et la form ------------------//
            $limitBillet = $formBillet - $NbreBillet;

        if ($limitBillet < 0){
            $this->addFlash('notice', 'Le nombre de billet doit être supérieur à votre choix précédent');
            return $this->redirectToRoute('my_app_billetterie_editcde', array('id' => $id,));
        }
            // ---------- CREATION ET STOCKAGE DES BILLETS DANS L'ARRYCOLLECTION ---------//
            for ($i = 0; $i < $limitBillet; $i++) {
                $actuCommande->addBillet(new Billet());
            }
            $em->flush();
           // dump($limitBillet);
            //return new Response('le nbre de billet en BDD est de : ' .$NbreBillet.
          //      ' <br />le nbr de billet dans le est de : '.$formBillet
         //   );
            $this->addFlash('notice', 'ETAPE 1 bien modifié');
            return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $id,));

        }
        return $this->render('MyAppBilletterieBundle:billetterie:etape1.html.twig', array(
            'form' => $form->createView(),));
    }

    /**
     * @Route("/cde/etape2/{id}", name="my_app_billetterie_billet", requirements={"id" = "\d+"})
     */
    public function billetAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $actuCommande = $em->getRepository('MyAppBilletterieBundle:Commande')
            ->findOneBy(array('id' => $id));

        if (null === $actuCommande) {
            throw new Exception("Cette commande n'existe pas !");
        }

        $listBillet = $em->getRepository('MyAppBilletterieBundle:Billet')
            ->findBy((array('commande'=>$id)));

        $form = $this->get('form.factory')->create(CommandeType::class, $actuCommande);
            $form->remove('dateVisite');
            $form->remove('typeJournee');
            $form->remove('nbBillet');
            $form->remove('email');

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($actuCommande);
                $em->flush();
            }
            $this->addFlash('notice', 'ETAPE 2 bien enregistrée');
            return $this->redirectToRoute('my_app_billetterie_recap', array('id' => $actuCommande->getId()));

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
           // $billet = new Billet();
           // $billet->setCommande($cde);
          //  $em->persist($billet);
            $cde->addBillet(new Billet());
        }
        $cde->setNbBillet($nbBillet);
        $em->flush();

        $this->addFlash('notice', 'Nouveau Billet enregistré');
        return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $id,));

        //return new Response('Nouveau Billet : ' .$nbBillet);
    }
    /**
     * @Route("/cde/etape2/deleteBillet/{id}", name="my_app_billetterie_deletebillet", requirements={"id" = "\d+"})
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