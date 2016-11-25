<?php

namespace MyApp\BilletterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LimiteBilletController extends Controller
{
    /**
     * @Route("/preCheckIn")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function preCheckinAction(Request $request)
    {

        if ($request->isXmlHttpRequest()){

            $date = new \DateTime($request->get('booking_date'));
            $nbPlace = $this->getDoctrine()->getRepository('MyAppBilletterieBundle:Billet')->countPlacesAt($date);
            if ($nbPlace < 1000){
                $status = 1;
            } else $status = 0;
            return new JsonResponse(['status'=> $status]);
        }

        return $this->redirectToRoute('app_bookink_homepage');

    }

}
