<?php
/**
 * Created by PhpStorm.
 * User: emmay
 * Date: 02/02/2019
 * Time: 17:54
 */

namespace BonPlansBundle\Entity;
use Doctrine\ORM\Mapping as ORM ;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BonPlan
 * @package BonPlansBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="bonplan")
 */

class BonPlan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private  $id ;

    /**
     * @ORM\Column(type="string",length=50,nullable=false)
     */
    private $name;
    /**
     * @ORM\Column(type="string",length=50)
     */
    private $adresse;
    /**
     * @ORM\Column(type="string",length=50)
     */
    private $phone;
    /**
     * @ORM\Column(type="integer" ,nullable=true)
     */
    private $note;
    /**
     * @ORM\Column(type="string",length=50,nullable=true)
     */
    private $description;
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Ajouter une image jpg")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $image;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private  $user;
    /**
     * @ORM\Column(type="string",length=50,nullable=true)
     */
    private $etoile;
    /**
    * @ORM\ManyToOne(targetEntity="Categorie")
    * @ORM\JoinColumn(name="categorie_id",referencedColumnName="id")
     */
   private $categorie;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $prix;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getEtoile()
    {
        return $this->etoile;
    }

    /**
     * @param mixed $etoile
     */
    public function setEtoile($etoile)
    {
        $this->etoile = $etoile;
    }



    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return mixed
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param mixed $categorie
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }



}