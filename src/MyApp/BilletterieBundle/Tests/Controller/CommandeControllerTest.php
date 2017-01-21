<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 26/12/2016
 * Time: 10:24
 */

namespace MyApp\BilletterieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use MyApp\BilletterieBundle\Entity\Commande;


class CommandeControllerTest extends WebTestCase
{
    protected $client;
    protected $form;
    protected $crawler;
    protected $value;

    protected function setUp()
    {
        $this->client = static::createClient();
        $url = $this->client->getContainer()->get('router')->generate('my_app_billetterie_cde');
        $crawler = $this->client->request('GET', $url);
        $form = $crawler->selectButton('ETAPE SUIVANTE')->form();
        $value = $form->getPhpValues();
        $value['myapp_billetteriebundle_commande[email]'] = 'atlantis11@libertysurf.fr';
        $value['myapp_billetteriebundle_commande[dateVisite]'] = '21-01-2017';
        $value['myapp_billetteriebundle_commande[typeJournee]'] = 'Demi-journee';
        $value['myapp_billetteriebundle_commande[nbBillet]'] = 2;
    }

    public function testCdePageResponseOk()
    {
        $this->assertTrue($this->client->getResponse()->isSuccessful());
        $this->assertContains('<title>Commande - Musee du Louvre</title>', $this->client->getResponse()->getContent());
    }

    public function testBillet()
    {
        // création d'un client
        $this->client = static::createClient();
        // Création d'une requete de la page formulaire
        $url = $this->client->getContainer()->get('router')->generate('my_app_billetterie_billet',array('id'=>37));
        $crawler = $this->client->request('GET', $url);
        // Sélection du formulaire
        $form = $crawler->selectButton('ETAPE SUIVANTE')->form();
        // Remplissage du formulaire
        $form['myapp_billetteriebundle_commande[billets][0][nom]'] = 'BERNARD';
        $form['myapp_billetteriebundle_commande[billets][0][prenom]'] = 'Philippe';
        $form['myapp_billetteriebundle_commande[billets][0][dateNaissance][day]'] = 25;
        $form['myapp_billetteriebundle_commande[billets][0][dateNaissance][month]'] = 12;
        $form['myapp_billetteriebundle_commande[billets][0][dateNaissance][year]'] = 1975;
        $form['myapp_billetteriebundle_commande[billets][0][isTarifReduit]'] = false;
        $form['myapp_billetteriebundle_commande[billets][0][pays]'] = 'FR';

        // Ajout d'un deuxieme billet
        $values = $form->getPhpValues();
        $values['commande[billets][0][nom]'] = 'BERNARD';
        $values['commande[billets][0][prenom]'] = 'Karine';
        $values['commande[billets][0][dateNaissance]'] = '25-11-1948';
        $values['commande[billets][0][reduction]'] = false;
        $values['commande[billets][0][pays]'] = 'FR';
        $this->client ->request($form->getMethod(), $form->getUri(), $values);
        $this->client ->followRedirect();
        $this->assertEquals('MyApp\BilletterieBundle\Controller\CommandeController::recapAction', $this->client ->getRequest()->attributes->get('_controller'));
    }

    public function testRecapPage()
    {
        $this->client = static::createClient();
        $url = $this->client->getContainer()->get('router')->generate('my_app_billetterie_recap',array('id'=>37));
        $crawler = $this->client->request('GET', $url);
        $this->assertEquals(' Numéro de votre Commande :', $crawler->filter('th')->text());
        $this->assertEquals('LOUVRE3408R1484929714', $crawler->filter('td')->text());
        $this->assertTrue(200 === $this->client->getResponse()->getStatusCode());
    }
}
