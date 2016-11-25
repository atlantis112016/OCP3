<?php
namespace MyApp\BilletterieBundle\DataFixtures\ORM;

use MyApp\BilletterieBundle\Entity\TypeTarif;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Created by PhpStorm.
 * User: Fabienne Bernard
 * Date: 23/11/2016
 * Time: 09:52
 */
class LoadTypeTarifData implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        // TODO: Implement load() method.
        $typeTarifs = array(
            array('normal', 16),
            array('enfant', 8),
            array('senior', 12),
            array('reduit', 10),
            array('gratuit', 0)
        );
        foreach ($typeTarifs as $typeTarif) {
            $newTypeTarif = new TypeTarif();

            foreach ($typeTarif as $cle => $valeur) {
                if ($cle === 0) {
                    $newTypeTarif->setNomType($valeur);
                }
                else {
                    $newTypeTarif->setMontant($valeur);
                }
            }
            $manager->persist($newTypeTarif);
        }
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        // TODO: Implement getOrder() method.
        return 1;
    }
}