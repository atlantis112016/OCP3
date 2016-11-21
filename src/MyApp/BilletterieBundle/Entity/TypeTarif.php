<?php

namespace MyApp\BilletterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * typeTarif
 *
 * @ORM\Table(name="type_tarif")
 * @ORM\Entity(repositoryClass="MyApp\BilletterieBundle\Repository\typeTarifRepository")
 */
class TypeTarif
{
    /**
     * @var int
     *
     * @ORM\Column(name="idTarif", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomType", type="string", length=255)
     */
    private $nomType;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", scale=2)
     */
    private $montant;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomType
     *
     * @param string $nomType
     *
     * @return typeTarifs
     */
    public function setNomType($nomType)
    {
        $this->nomType = $nomType;

        return $this;
    }

    /**
     * Get nomType
     *
     * @return string
     */
    public function getNomType()
    {
        return $this->nomType;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return typeTarifs
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }
}

