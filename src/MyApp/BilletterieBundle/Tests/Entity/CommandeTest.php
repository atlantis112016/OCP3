<?php
/**
 * Created by PhpStorm.
 * User: MisterX
 * Date: 17/12/2016
 * Time: 17:43
 */

namespace MyApp\BilletterieBundle\Tests\Entity;


use MyApp\BilletterieBundle\Entity\Commande;


class CommandeTest extends \PHPUnit_Framework_TestCase
{
    public function testCommande()
    {
        $cde = new Commande();
        $cde
            ->setCodeReserv('LOUVRE12345')
            ->setDatereserv('16-12-2016 12:00')
            ->setDateVisite('19-12-2016')
            ->setEmail('captainAmerica@gmail.com')
            ->setNbBillet('2');
        $this->assertEquals('LOUVRE12345', $cde->getCodeReserv());
        $this->assertEquals('16-12-2016 12:00', $cde->getDatereserv());
        $this->assertEquals('19-12-2016', $cde->getDateVisite());
        $this->assertEquals('captainAmerica@gmail.com', $cde->getEmail());
        $this->assertEquals('2', $cde->getNbBillet());
    }
}
