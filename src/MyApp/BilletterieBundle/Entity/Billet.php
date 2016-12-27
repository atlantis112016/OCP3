<?php

namespace MyApp\BilletterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use MyApp\BilletterieBundle\Validator\DateAnniv;

/**
 * Billet
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MyApp\BilletterieBundle\Repository\BilletRepository")
 */
class Billet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $prenom;

    /**
     * @var \DateTime
     * @Assert\DateTime(message="Ce champ doit être une date au format :d-m-Y")
     * @DateAnniv()
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateNaissance;

     /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isTarifReduit;

    /**
     * @var string
     * @Assert\Country(message="Ce champ doit être de Type (ex: France = FR)")
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $pays;

    /**
     * @var integer
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    private $montant;

      /**
       * @ORM\ManyToOne(targetEntity="MyApp\BilletterieBundle\Entity\Commande", inversedBy="billets", cascade={"persist"})
       *
       */
      private $commande;

      /**
       * @var string
       * @ORM\Column(type="string", length=15, nullable=true)
       */
      private $typeTarif;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Billet
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Billet
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Billet
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

     /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Billets
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set commande
     *
     * @param \MyApp\BilletterieBundle\Entity\commande $commande
     *
     * @return Billet
     */
    public function setCommande(\MyApp\BilletterieBundle\Entity\commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \MyApp\BilletterieBundle\Entity\commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set typeTarif
     *
     * @param string $typeTarif
     *
     * @return Billet
     */
    public function setTypeTarif($typeTarif)
    {
        $this->typeTarif = $typeTarif;

        return $this;
    }

    /**
     * Get typeTarif
     *
     * @return string
     */
    public function getTypeTarif()
    {
        return $this->typeTarif;
    }

    /**
     * Set isTarifReduit
     *
     * @param boolean $isTarifReduit
     *
     * @return Billet
     */
    public function setTarifReduit($isTarifReduit)
    {
        $this->isTarifReduit = $isTarifReduit;

        return $this;
    }

    /**
     * Get isTarifReduit
     *
     * @return boolean
     */
    public function getTarifReduit()
    {
        return $this->isTarifReduit;
    }

     /**
     * Set isTarifReduit
     *
     * @param boolean $isTarifReduit
     *
     * @return Billet
     */
    public function setIsTarifReduit($isTarifReduit)
    {
        $this->isTarifReduit = $isTarifReduit;

        return $this;
    }

    /**
     * Get isTarifReduit
     *
     * @return boolean
     */
    public function getIsTarifReduit()
    {
        return $this->isTarifReduit;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return Billet
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer
     */
    public function getMontant()
    {
        return $this->montant;
    }
}
