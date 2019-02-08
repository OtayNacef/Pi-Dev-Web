<?php
/**
 * Created by PhpStorm.
 * User: Bhs Nada
 * Date: 03/02/2019
 * Time: 10:24 AM
 */

namespace HotesBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class MaisonsHotes
 * @package HotesBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="maisons_hotes")
 */

class MaisonsHotes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
private  $id;
    /**
     * @ORM\Column(type="string",length=150, nullable=true)
     */
private  $nom;
    /**
     * @ORM\Column(type="string",length=500, nullable=true)
     */
    private  $description;
    /**
     * @ORM\Column(type="string",length=150, nullable=true)
     */
private $pays;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
private $capacites;


    /**
     * @ORM\Column(type="string",length=50, nullable=true)
     */
    private  $site_web;






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
     * @ORM\Column(type="integer")
     */
private $tel;
    /**
     * @ORM\Column(type="string",length=150, nullable=true)
     */
private $mail;
    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload images")
     * @Assert\File()
     */
private $image;

    /**
     * @return mixed
     */
    public function getSiteWeb()
    {
        return $this->site_web;
    }

    /**
     * @param mixed $site_web
     */
    public function setSiteWeb($site_web)
    {
        $this->site_web = $site_web;
    }

    /**
     * @ORM\Column(type="integer",length=150, nullable=true)
     */
    private $prix;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\user")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @ORM\Column(type="string",length=500)
     */
private $adresse;

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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
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
     * @return mixed
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @param mixed $pays
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    /**
     * @return mixed
     */
    public function getCapacites()
    {
        return $this->capacites;
    }

    /**
     * @param mixed $capacites
     */
    public function setCapacites($capacites)
    {
        $this->capacites = $capacites;
    }



    /**
     * @return mixed
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
    /**
     * @ORM\Column(type="string",length=150, nullable=true)
     */
private  $gouvernorat;

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
    public function getGouvernorat()
    {
        return $this->gouvernorat;
    }

    /**
     * @param mixed $gouvernorat
     */
    public function setGouvernorat($gouvernorat)
    {
        $this->gouvernorat = $gouvernorat;
    }




}