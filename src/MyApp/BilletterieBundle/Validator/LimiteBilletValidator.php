<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 13/12/2016
 * Time: 20:39
 */

namespace MyApp\BilletterieBundle\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use MyApp\BilletterieBundle\Entity\Commande;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class LimiteBilletValidator extends ConstraintValidator
{
    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }
    public function validate($value, Constraint $constraint)
    {
        //--------- Récupération du seuil max ------//
        $maxi = Commande::MAX_BILLETS;
        $request = $this->requestStack->getCurrentRequest();
        $dateVisite = $request->request->get('dateVisite');
        //------ Liste les billets déjà enregistrés à la date du jour ---------//
        $listReserv = $this->em->getRepository('MyAppBilletterieBundle:Commande')
            ->findBy(array('dateVisite' => $dateVisite));

        $totalBillet=0;

        foreach ($listReserv as $command) {
            $billetQuantite = $command->getNbBillet();
            $totalBillet += $billetQuantite;
        }

        if (($totalBillet + $value) > $maxi) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message
            $this->context->addViolation($constraint->message);
        }

    }
}