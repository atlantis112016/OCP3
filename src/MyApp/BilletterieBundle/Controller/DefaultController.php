<?php

namespace MyApp\BilletterieBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MyAppBilletterieBundle:billetterie:index.html.twig');
    }

        public function formAction()
    {
        return $this->render('MyAppBilletterieBundle:billetterie:form.html.twig');
    }

        public function tarifAction()
    {
        return $this->render('MyAppBilletterieBundle:billetterie:tarif.html.twig');
    }

        public function mentionAction()
    {
        return $this->render('MyAppBilletterieBundle:billetterie:mention.html.twig');
    }

}
