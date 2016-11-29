<?php

namespace MyApp\BilletterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Billet
 *
 * @ORM\Table(name="billet")
 * @ORM\Entity(repositoryClass="MyApp\BilletterieBundle\Repository\BilletRepository")
 */
class Billet
{
    /**
     * @var int
     *
     * @ORM\Column(name="idBillet", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank()(message="Ce champ ne peut pas être vide")
     *
     * @ORM\Column(name="nom", type="string", length=30)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank()(message="Ce champ ne peut pas être vide")
     * @ORM\Column(name="prenom", type="string", length=30)
     */
    private $prenom;

    /**
     * @var \DateTime
     * @Assert\DateTime()(message="Ce champ doit être une date au format :dd/mm/yyyy")
     *
     * @ORM\Column(name="dateNaissance", type="datetime")
     */
    private $dateNaissance;

     /**
     * @var bool
     * @ORM\Column(name="reduit", type="boolean")
     */
    private $tarifReduit;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=30)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_billet", type="integer")
     */
    private $montantBillet;

      /**
       * @ORM\ManyToOne(targetEntity="MyApp\BilletterieBundle\Entity\commande", cascade={"persist"})
       * @ORM\JoinColumn(name="idCde", referencedColumnName="idCde")
       */
      private $commande;

      /**
       * @ORM\ManyToOne(targetEntity="MyApp\BilletterieBundle\Entity\typeTarif", cascade={"persist"})
       * @ORM\JoinColumn(name="typeTarif_id", referencedColumnName="idTarif")
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
     * @param \MyApp\BilletterieBundle\Entity\typeTarif $typeTarif
     *
     * @return Billet
     */
    public function setTypeTarif(\MyApp\BilletterieBundle\Entity\typeTarif $typeTarif = null)
    {
        $this->typeTarif = $typeTarif;

        return $this;
    }

    /**
     * Get typeTarif
     *
     * @return \MyApp\BilletterieBundle\Entity\typeTarif
     */
    public function getTypeTarif()
    {
        return $this->typeTarif;
    }

    /**
     * Set tarifReduit
     *
     * @param boolean $tarifReduit
     *
     * @return Billet
     */
    public function setTarifReduit($tarifReduit)
    {
        $this->tarifReduit = $tarifReduit;

        return $this;
    }

    /**
     * Get tarifReduit
     *
     * @return boolean
     */
    public function getTarifReduit()
    {
        return $this->tarifReduit;
    }

    /**
     * Set montantBillet
     *
     * @param integer $montantBillet
     *
     * @return Billet
     */
    public function setMontantBillet($montantBillet)
    {
           $this->montantBillet = $montantBillet;
        return $this;
    }

    /**
     * Get montantBillet
     *
     * @return integer
     */
    public function getMontantBillet()
    {
        return $this->montantBillet;
    }
}
