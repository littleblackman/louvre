<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commandes")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandesRepository")
 */
class Commande
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
     * @var bool
     *
     * @ORM\Column(name="valide", type="boolean")
     */
    private $valide = 1;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateBillet", type="datetime")
     */
    private $dateBillet;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Utilisateur", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Billet", mappedBy="commande", cascade={"persist", "remove"})
     * @ORM\JoinColumn()
     */
    private $billet;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->billet = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
        $this->dateBillet = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set valide
     *
     * @param boolean $valide
     *
     * @return Commande
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Get valide
     *
     * @return boolean
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set dateBillet
     *
     * @param \DateTime $dateBillet
     *
     * @return Commande
     */
    public function setDateBillet($dateBillet)
    {
        $this->dateBillet = $dateBillet;

        return $this;
    }

    /**
     * Get dateBillet
     *
     * @return \DateTime
     */
    public function getDateBillet()
    {
        return $this->dateBillet;
    }

    /**
     * Set utilisateur
     *
     * @param \AppBundle\Entity\Utilisateur $utilisateur
     *
     * @return Commande
     */
    public function setUtilisateur(\AppBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \AppBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Add billet
     *
     * @param \AppBundle\Entity\Billet $billet
     *
     * @return Commande
     */
    public function addBillet(\AppBundle\Entity\Billet $billet)
    {
        $this->billet[] = $billet;
        $billet->setCommande($this);
        return $this;
    }

    /**
     * Remove billet
     *
     * @param \AppBundle\Entity\Billet $billet
     */
    public function removeBillet(\AppBundle\Entity\Billet $billet)
    {
        $this->billet->removeElement($billet);
    }

    /**
     * Get billet
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBillet()
    {
        return $this->billet;
    }
}
