<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * billets
 *
 * @ORM\Table(name="billets")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BilletsRepository")
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
     * @ORM\Column(name="nom", type="string", length=45)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=45)
     */
    private $prenom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateNaissance", type="datetime")
     */
    private $dateNaissance;

    /**
     * @var bool
     *
     * @ORM\Column(name="disponible", type="boolean")
     */
    private $disponible = true;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
   private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Commande", inversedBy="billet", cascade={"persist"})
     */
   private $commande;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->prix = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set disponible
     *
     * @param boolean $disponible
     *
     * @return Billet
     */
    public function setDisponible($disponible)
    {
        $this->disponible = $disponible;

        return $this;
    }

    /**
     * Get disponible
     *
     * @return boolean
     */
    public function getDisponible()
    {
        return $this->disponible;
    }

    /**
     * Add prix
     *
     * @param \AppBundle\Entity\Prix $prix
     *
     * @return Billet
     */
    public function addPrix(\AppBundle\Entity\Prix $prix)
    {
        $this->prix[] = $prix;

        return $this;
    }

    /**
     * Remove prix
     *
     * @param \AppBundle\Entity\Prix $prix
     */
    public function removePrix(\AppBundle\Entity\Prix $prix)
    {
        $this->prix->removeElement($prix);
    }

    /**
     * Get prix
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return Billet
     */
    public function setCommande(\AppBundle\Entity\Commande $commande)
    {
        $this->commande = $commande;
        return $this;
    }

    /**
     * Get commande
     *
     * @return \AppBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    public function getAge()
    {
        $age = date('Y') - date('Y', strtotime($this->getDateNaissance()));
        if (date('md') < date('md', strtotime($this->getDateNaissance())))
        {
            return $age -1;
        }
        return $age;
    }
}
