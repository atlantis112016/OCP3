<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 16/12/2016
 * Time: 12:33
 */

namespace MyApp\BilletterieBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use MyApp\BilletterieBundle\Entity\Commande;

class MailConfirmation
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    private $twig;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function confirmMail(Commande $recapCde)
    {
        $message = \Swift_Message::newInstance();
        $imgUrl = $message->embed(\Swift_Image::fromPath('bundles/myappbilletterie/images/logo50.jpg'));
        $message->setSubject("Confirmation de votre commande")
            ->setFrom('serviceClient@museedulouvre.com')
            ->setTo($recapCde->getEmail())
            ->setCharset('utf-8')
            ->setContentType('text/html')
            ->setBody($this->twig->render('@MyAppBilletterie/CommandeController/email.html.twig',
                array('recapCde' => $recapCde, 'url'=>$imgUrl),'text/html'));

        //envoi du message
        $this->mailer->send($message);
    }
}