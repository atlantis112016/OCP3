<?php
/**
 * Created by PhpStorm.
 * User: Fabienne BERNARD
 * Date: 17/12/2016
 * Time: 18:12
 */

namespace MyApp\BilletterieBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use MyApp\BilletterieBundle\Entity\Commande;
use MyApp\BilletterieBundle\Services;
use Doctrine\Common\Collections\ArrayCollection;

class LimiteBilletTest extends WebTestCase
{
    protected $client;
    public function testlimiteBilletAtteint() {
        $dateVisite = new \DateTime('2016-12-28');
        $nbBillet = 1001;

        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $testLimite = $container->get('my_app_billetterie.limitebillet');
        $this->assertEquals(true,$testLimite->isLimiteBillet($dateVisite, $nbBillet, 0));
        }
    public function testlimiteBilletNonAtteint()
    {
        $dateVisite = new \DateTime('2016-12-22');
        $nbBillet = 998;

        $this->client = static::createClient();
        $container = $this->client->getContainer();
        $testLimite = $container->get('my_app_billetterie.limitebillet');
        $this->assertEquals(false,$testLimite->isLimiteBillet($dateVisite, $nbBillet, 0));

    }
}
