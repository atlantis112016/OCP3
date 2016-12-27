<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 17/12/2016
 * Time: 17:43
 */

namespace MyApp\BilletterieBundle\Tests\Entity;


use MyApp\BilletterieBundle\Entity\Billet;


class BilletTest extends \PHPUnit_Framework_TestCase
{
    public function testBillet()
    {
        $billet = new Billet();
        $billet
            ->setNom('Captain')
            ->setPrenom('America')
            ->setPays('fr')
            ->setTypeTarif('sénior')
            ->setMontant('12')
            ->setDateNaissance('29-11-1976');
        $this->assertEquals('Captain', $billet->getNom());
        $this->assertEquals('America', $billet->getPrenom());
        $this->assertEquals('fr', $billet->getPays());
        $this->assertEquals('sénior', $billet->getTypeTarif());
        $this->assertEquals('12', $billet->getMontant());
        $this->assertEquals('29-11-1976', $billet->getDateNaissance());
    }
}
