<?php

namespace MyApp\BilletterieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
   /* /**
     * @dataProvider urlProvider
     */
   /* public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider()
    {
        return array(
            array('/'),
            array('/cde/etape1'),
            array('/mention'),
            array('/tarif'),
        );
    }*/
    public function testHome()
    {
        $client = self::createClient();
        $url = $client->getContainer()->get('router')->generate('my_app_billetterie_home');
        $client->request('GET', $url);
    }

    public function testMention()
    {
        $client = self::createClient();
        $url = $client->getContainer()->get('router')->generate('my_app_billetterie_mention');
        $client->request('GET', $url);
    }

    public function testTarif()
    {
        $client = self::createClient();
        $url = $client->getContainer()->get('router')->generate('my_app_billetterie_tarif');
        $client->request('GET', $url);
    }

    public function testCde()
    {
        $client = self::createClient();
        $url = $client->getContainer()->get('router')->generate('my_app_billetterie_cde');
        $client->request('GET', $url);
    }

    public function testPaiement()
    {
        $client = self::createClient();
        $url = $client->getContainer()->get('router')->generate('my_app_billetterie_paiement', array('id'=>5));
        $client->request('GET', $url);
    }
}
