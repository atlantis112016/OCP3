<?php

namespace MyApp\BilletterieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="MyApp\BilletterieBundle\Repository\commandeRepository")
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="idCde", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="datereserv", type="datetime")
     */
    private $datereserv;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=50)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="montantTotal", type="decimal", scale=2)
     */
    private $montantTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="codeReserv", type="string", length=255)
     */
    private $codeReserv;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255)
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="tokenStripe", type="string", length=255)
     */
    private $tokenStripe;

     /**
     * @var integer
     *
     * @ORM\Column(name="nbBillet", type="integer")
     */
    private $nbBillet;

   /**
    * @ORM\OneToMany(targetEntity="MyApp\BilletterieBundle\Entity\Billet", mappedBy="commande", cascade={"persist"})
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
     * Set datereserv
     *
     * @param \DateTime $datereserv
     *
     * @return commandes
     */
    public function setDatereserv($datereserv)
    {
        $this->datereserv = $datereserv;

        return $this;
    }

    /**
     * Get datereserv
     *
     * @return \DateTime
     */
    public function getDatereserv()
    {
        return $this->datereserv;
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
}
