<?php

namespace MyApp\BilletterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * commande
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="MyApp\BilletterieBundle\Repository\CommandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\DateTime(message="Ce champ doit être une date au format :dd-mm-yyyy")
     * @ORM\Column(type="datetime")
     */
    private $dateReserv;

    /**
     * @var string
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\Email(message="Merci de taper un mail valide")
     * @ORM\Column(type="string", length=50)
     */
    private $email;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide")
     * @Assert\DateTime(message="Ce champ doit être une date au format :dd-mm-yyyy")
     * @ORM\Column(type="datetime")
     */
    private $dateVisite;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    private $montantTotal = 0.00;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30)
     */
    private $typeJournee;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $codeReserv;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $tokenStripe;

     /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $nbBillet;

   /**
    * @ORM\OneToMany(targetEntity="MyApp\BilletterieBundle\Entity\Billet",mappedBy="commande", cascade={"persist"}, orphanRemoval=true)
    * 
    */
      private $billets;


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
     * Set dateReserv
     *
     * @param \DateTime $dateReserv
     *
     * @return commandes
     */
    public function setDatereserv($dateReserv)
    {
        $this->dateReserv = $dateReserv;

        return $this;
    }

    /**
     * Get dateReserv
     *
     * @return \DateTime
     */
    public function getDatereserv()
    {
        return $this->dateReserv;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return commandes
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set montantTotal
     *
     * @param string $montantTotal
     *
     * @return commandes
     */
    public function setMontantTotal($montantTotal)
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    /**
     * Get montantTotal
     *
     * @return string
     */
    public function getMontantTotal()
    {
        return $this->montantTotal;
    }

    /**
     * Set codeReserv
     *
     * @param string $codeReserv
     *
     * @return commandes
     */
    public function setCodeReserv($codeReserv)
    {
        $this->codeReserv = $codeReserv;

        return $this;
    }

    /**
     * Get codeReserv
     *
     * @return string
     */
    public function getCodeReserv()
    {
        return $this->codeReserv;
    }

    /**
     * Set statut
     *
     * @param string $statut
     *
     * @return commandes
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return string
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set tokenStripe
     *
     * @param string $tokenStripe
     *
     * @return commandes
     */
    public function setTokenStripe($tokenStripe)
    {
        $this->tokenStripe = $tokenStripe;

        return $this;
    }

    /**
     * Get tokenStripe
     *
     * @return string
     */
    public function getTokenStripe()
    {
        return $this->tokenStripe;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->billets = new ArrayCollection();
        $this->dateReserv = new \DateTime();
    }

    /**
     * Add billet
     *
     * @param \MyApp\BilletterieBundle\Entity\Billet $billet
     *
     * @return Commande
     */
    public function addBillet(\MyApp\BilletterieBundle\Entity\Billet $billet)
    {
        $this->billets[] = $billet;
        $billet->setCommande($this);
        return $this;
    }

    /**
     * Remove billet
     *
     * @param \MyApp\BilletterieBundle\Entity\Billet $billet
     */
    public function removeBillet(\MyApp\BilletterieBundle\Entity\Billet $billet)
    {
        $this->billets->removeElement($billet);
        $billet->setParent(null);
    }

    /**
     * Get billets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillets()
    {
        return $this->billets;
    }

    /**
     * Set nbBillet
     *
     * @param integer $nbBillet
     *
     * @return Commande
     */
    public function setNbBillet($nbBillet)
    {
        $this->nbBillet = $nbBillet;

        return $this;
    }

    /**
     * Get nbBillet
     *
     * @return integer
     */
    public function getNbBillet()
    {
        return $this->nbBillet;
    }

    /**
     * Set dateVisite
     *
     * @param \DateTime $dateVisite
     *
     * @return Commande
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
     * Set typeJournee
     *
     * @param string $typeJournee
     *
     * @return Commande
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
}
