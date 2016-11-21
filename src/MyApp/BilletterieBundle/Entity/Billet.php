<?php

namespace MyApp\BilletterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @var \DateTime
     *
     * @ORM\Column(name="dateVisite", type="datetime")
     */
    private $dateVisite;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=30)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="datetime")
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="typeJournee", type="string", length=30)
     */
    private $typeJournee;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=30)
     */
    private $pays;

      /**
       * @ORM\ManyToOne(targetEntity="MyApp\BilletterieBundle\Entity\commande", cascade={"persist"})
       * @ORM\JoinColumn(name="Cde_id", referencedColumnName="id")
       */
      private $commande;

      /**
       * @ORM\ManyToOne(targetEntity="MyApp\BilletterieBundle\Entity\typeTarif", cascade={"persist"})
       * @ORM\JoinColumn(name="typeTarif_id", referencedColumnName="id")
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
     * Set dateVisite
     *
     * @param \DateTime $dateVisite
     *
     * @return Billet
     */
    public function setDateVisite($dateVisite)
    {
        $this->dateVisite = $dateVisite;

        return $this;
    }

    /**
     * Get dateVisite
     *
     * @return \DateTime
     */
    public function getDateVisite()
    {
        return $this->dateVisite;
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
     * Set typeJournee
     *
     * @param string $typeJournee
     *
     * @return Billet
     */
    public function setTypeJournee($typeJournee)
    {
        $this->typeJournee = $typeJournee;

        return $this;
    }

    /**
     * Get typeJournee
     *
     * @return string
     */
    public function getTypeJournee()
    {
        return $this->typeJournee;
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
     * Set commande_id
     *
     * */
  public function setCommande(Advert $advert)
  {
    $this->commande = $commande;
    return $this;
  }

    /**
     * get commande_id
     *
     * */
  public function getCommande()
  {
    return $this->commande;
  }

  public function setTypeTarif(Advert $advert)
  {
    $this->typeTarif = $typeTarif;
    return $this;
  }

    /**
     * get typeTarif_id
     *
     * */
  public function getTypeTarif()
  {
    return $this->typeTarif;
  }
}

