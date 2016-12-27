<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 12/12/2016
 * Time: 09:56
 */

namespace MyApp\BilletterieBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use MyApp\BilletterieBundle\Entity\Commande;

class Stripe
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function Token($id, $secretKey)
    {
        //------ Liste les billets déjà enregistrés à la date du jour ---------//
        $cde = $this->em->getRepository('MyAppBilletterieBundle:Commande')
            ->findOneBy(array('id' => $id));
        if ($cde->getTokenStripe() === '00000000000000') {
        \Stripe\Stripe::setApiKey($secretKey);
        $token  = $_POST['stripeToken'];
        $customer = \Stripe\Customer::create(array(
            'email' => $cde->getEmail(),
            'source'  => $token
        ));

         \Stripe\Charge::create(array(
            'customer' => $customer->id,
            'amount'   => $cde->getMontantTotal()*100,
            'currency' => 'eur'
        ));

        $cde->setTokenStripe($token);
        $cde->setStatut(Commande::STATUT_PAIEMENT);
        $this->em->flush();
        return $token;
        }
        return $token = $cde->getTokenStripe();
    }
}