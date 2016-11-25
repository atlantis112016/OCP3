<?php

namespace MyApp\BilletterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



class DefaultController extends Controller
{
    /**      
    * @Route("/", name="my_app_billetterie_home")     
    */
    public function indexAction()
    {
        return $this->render('MyAppBilletterieBundle:billetterie:index.html.twig');
    }


/**      
* @Route("/tarif", name="my_app_billetterie_tarif")      
*/
        public function tarifAction()
    {
        return $this->render('MyAppBilletterieBundle:billetterie:tarif.html.twig');
    }
/**      
* @Route("/mention", name="my_app_billetterie_mention")      
*/
        public function mentionAction()
    {
        return $this->render('MyAppBilletterieBundle:billetterie:mention.html.twig');
    }

}
