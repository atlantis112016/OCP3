<?php

namespace MyApp\BilletterieBundle\Controller;

use MyApp\BilletterieBundle\Entity\Billet;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;


class BilletController extends Controller
{
    /**
     * @Route("/cde/{id}", name="my_app_billetterie_cde")
     */
    public function addAction(Request $request, $etape)
  {
    // On crée un objet Billet
    $billet = new Billet();
    $form = $this->get('form.factory')->createBuilder(FormType::class, $billet)
      ->add('prenom', TextType::class)
      ->add('nom', TextType::class)
      ->add('dateNaissance', BirthdayType::class, array (
            'format' => 'dd-MM-yyyy',
            'years' => range(date('Y'), date('Y')-80)
            ))
      ->add('tarifReduit', CheckboxType::class, array(
            'required' => false,
            ))
      ->add('save',      SubmitType::class)
      ->getForm();

    
    // Si la requête est en POST
    if ($request->isMethod('POST')) {
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $billet contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      // On vérifie que les valeurs entrées sont correctes
      if ($form->isValid()) {
        // On enregistre notre objet $billet dans la base de données, par exemple
        $em = $this->getDoctrine()->getManager();
        $em->persist($billet);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', '1ère étape enregistrée.');

        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('my_app_billetterie_form', array($etape===2, 'id' => $billet->getId()));
      }
    }

    // À ce stade, le formulaire n'est pas valide car :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
    return $this->render('MyAppBilletterieBundle:billetterie:etape2.html.twig', array(
      'form' => $form->createView(),
    ));
  }
}