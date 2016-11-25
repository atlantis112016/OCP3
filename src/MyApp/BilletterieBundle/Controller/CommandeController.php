<?php

namespace MyApp\BilletterieBundle\Controller;

use MyApp\BilletterieBundle\Entity\Billet;
use MyApp\BilletterieBundle\Entity\Commande;
use MyApp\BilletterieBundle\Form\CommandeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType ;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class CommandeController extends Controller
{
    /**
     * @Route("/cde", name="my_app_billetterie_cde")
     */
    public function formAction(Request $request)
    {
        $cde = new Commande();
        /*$form = $this -> createFormBuilder ( $cde )
            ->add('datereserv', DateType::class, array ( 'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
                'placeholder' => 'Choisissez une date',
                'attr' => [ 'class' => 'js-datepicker']))
            ->add('email', EmailType::class,
                array( 'required' => true,
                        'label'  => 'Votre E-mail'))
            ->add('montantTotal', TextType::class,
                array( 'required' => true,
                        'label'  => 'Montant Total'))
            ->add('codeReserv', TextType::class,
                array( 'required' => true,
                    'label'  => 'Code de la réservation'))
            ->add('statut', TextType::class,
                array( 'required' => true,
                    'label'  => 'Statut de la commande'))
            ->add('tokenStripe', TextType::class,
                array( 'required' => true,
                    'label'  => 'Code Stripe'))
            ->add('save', SubmitType::class, array ( 'label' => 'Etape Suivante' ))
            -> getForm ();

        /*Pour mettre plusieurs champs = nbre de billets par exemple
         * $builder -> add ( 'emails' , CollectionType :: class , array (
            // each entry in the array will be an "email" field
            'entry_type'   => EmailType :: class ,
            // these options are passed to each "email" type
            'entry_options'  => array (
                'attr'      => array ( 'class' => 'email-box' )
            ),*/
        $form = $this->createForm(CommandeType:: class, $cde);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $cde = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($cde);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'ETAPE 1 bien enregistrée');
                return $this->redirectToRoute('my_app_billetterie_billet', array('id' => $cde->getId()));
            }

        }
        return $this->render('MyAppBilletterieBundle:billetterie:etape1.html.twig', array(
            'form' => $form->createView(),));
    }
}