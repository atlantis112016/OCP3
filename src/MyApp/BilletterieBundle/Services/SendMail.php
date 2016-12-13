<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 09/12/2016
 * Time: 19:07
 */

namespace MyApp\BilletterieBundle\Services;

use MyApp\BilletterieBundle\Entity\Billet;
use MyApp\BilletterieBundle\Entity\Commande;
use Symfony\Component\Templating\EngineInterface;

class SendMail
{
    protected $mailer;
    protected $templating;
    private $from = "contact@museedulouvre.fr";
    private $reply = "contact@museedulouvre.fr";
    private $name = "Service client du Musée";
    private $imgUrl;

    public function __construct($mailer, $imgUrl, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->imgUrl = $imgUrl;
        $this->templating = $templating;

    }

    protected function sendMessage($to, $subject, $body, $imgUrl)
    {
        //------------- Création d'une nouvelle instance de mail ----------------//
        $mail = \Swift_Message::newInstance();
        $this->imgUrl = $mail->embed(\Swift_Image::fromPath('bundles/myappbilletterie/images/logo50.jpg'));
        //----------- Construction de notre mail ----------------//
        $mail
            ->setFrom($this->from)
            ->setTo($to)
            ->setSubject($subject)
            ->setBody($body, $imgUrl)
            ->setReplyTo($this->reply,$this->name)
            ->setContentType('text/html');

        $this->mailer->send($mail);
}
    public function sendSuccessMessage(Commande $id, Billet $billet){
        $subject = "Votre commande a bien été enregistrée";
        $template = 'MyAppBilletterieBundle:billetterie:email.html.twig';
        $to = $id->getEmail();
        $body = $this->templating->renderView($template, array(
                'recapCde' => $id, 'recapBillets'=>$billet, 'url'=>$imgUrl));
        $this->sendMessage($to, $subject, $body);
}
}