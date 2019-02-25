<?php

namespace ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier")
 * @ORM\Entity(repositoryClass="ShopBundle\Repository\PanierRepository")
 */
class Panier
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id"  ,referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="ShopBundle\Entity\Produit", cascade={"persist"})
     * @ORM\JoinColumn(name="produit_id" ,referencedColumnName="id" , nullable=true, onDelete="CASCADE")
     */
    private $produitP;


    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var int
     *
     * @ORM\Column(name="prix", type="integer")
     */
    private $prix;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_p", type="datetime")
     */
    private $dateP;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getProduitP()
    {
        return $this->produitP;
    }

    /**
     * @param mixed $produitP
     */
    public function setProduitP($produitP): void
    {
        $this->produitP = $produitP;
    }

    /**
     * @return int
     */
    public function getQuantite(): int
    {
        return $this->quantite;
    }

    /**
     * @param int $quantite
     */
    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    /**
     * @return \DateTime
     */
    public function getDateP(): \DateTime
    {
        return $this->dateP;
    }

    /**
     * @param \DateTime $dateP
     */
    public function setDateP(\DateTime $dateP): void
    {
        $this->dateP = $dateP;
    }

    /**
     * @return int
     */
    public function getPrix(): int
    {
        return $this->prix;
    }

    /**
     * @param int $prix
     */
    public function setPrix(int $prix): void
    {
        $this->prix = $prix;
    }


}
