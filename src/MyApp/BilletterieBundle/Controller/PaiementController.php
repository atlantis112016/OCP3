<?php

namespace MyApp\BilletterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Payum\Core\Request\GetHumanStatus;
use AppBundle\Entity\Reservation;
use AppBundle\Entity\CompteReservation;
use AppBundle\Event\ReservationEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaiementController extends Controller
{
    public function prepareAction(Reservation $reservation)
    {
        if ($reservation === null)
        {
            throw new NotFoundHttpException('La réservation '.$reservation->getId().' n\'a pas été trouver');
        }
        $gatewayName = "stripe";

        $storage = $this->get('payum')->getStorage('AppBundle\Entity\Payment');

        $payment = $storage->create();
        $payment->setNumber(uniqid());
        $payment->setCurrencyCode('EUR');
        $payment->setTotalAmount(number_format($reservation->getPrix(),"2",'',''));
        $payment->setDescription('Billet du louvre');
        $payment->setClientId($reservation->getId());
        $payment->setClientEmail($reservation->getEmail());
        $storage->update($payment);

        $captureToken = $this->get('payum')->getTokenFactory()->createCaptureToken(
            $gatewayName, $payment, 'app_done'
    );
        return $this->redirect($captureToken->getTargetUrl());
    }

    public function doneAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $token = $this->get('payum')->getHttpRequestVerifier()->verify($request);

        $gateway = $this->get('payum')->getGateway($token->getGatewayName());

        $gateway->execute($status = new GetHumanStatus($token));

        $payment = $status->getFirstModel();
        $reservation = $em->getRepository('AppBundle\Entity\Reservation')->findOneBy(array('id' => $payment->getClientId()));
        if ($reservation === null)
        {
            throw new NotFoundHttpException('La réservation '.$reservation->getId().' n\'a pas été trouver');
        }


        if ($status->isCaptured()) {
            $reservation->addPayement();

            $dateReservation = $em->getRepository('AppBundle:CompteReservation')->findOneBy(array('dateReservation' => $reservation->getDateReservation()));
            if ($dateReservation === null) {
                $dateReservation = new CompteReservation();
                $dateReservation->setDateReservation($reservation->getDateReservation());
                $dateReservation->setTotal($reservation->getBillets()->count());
            } else {
                $dateReservation->setTotal($reservation->getBillets()->count());
            }
            $em->persist($dateReservation);
            $em->flush();

            $this->get('event_dispatcher')->dispatch('reservation.captured', new ReservationEvent($reservation));

            return $this->redirectToRoute('app_recapitulatif', array('id' => $reservation->getId()));
        } else {
            return $this->redirectToRoute('app_confirmation', array('id' => $reservation->getId()));
        }
    }

    public function recapitulatifAction(Reservation $reservation)
    {
        if ($reservation === null)
        {
            throw new NotFoundHttpException('La réservation '.$reservation->getId().' n\'a pas été trouver');
        }
        return $this->render('AppBundle:App:payer.html.twig', array('reservation' => $reservation));
    }

}