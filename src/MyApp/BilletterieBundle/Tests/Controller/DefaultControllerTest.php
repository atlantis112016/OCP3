<?php

namespace MyApp\BilletterieBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
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
        $url = $client->getContainer()->get('router')->generate('my_app_billetterie_paiement', array('id'));
        $client->request('GET', $url);
    }
}
