<?php
// src/AppBundle/Entity/User.php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\NotifiableInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser implements NotifiableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=100, nullable=true)
     */
    private $prenom;
    /**
     * @var Date
     *
     * @ORM\Column(name="date_naissance", type="date")
     */
    protected $date_naissance;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string" , length=255,nullable=true)
     */
    protected $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string" , length=255,nullable=true)
     */
    protected $region;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string" , length=255,nullable=true)
     */
    protected $ville;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string" , length=255,nullable=true)
     */
    protected $description;
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string" , length=255,nullable=true)
     */
    protected $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="Gender", type="string" , length=255,nullable=true)
     */
    protected $Gender;

    /**
     * @var string
     *
     * @ORM\Column(name="religion", type="string" , length=255,nullable=true)
     */
    protected $relegion;
    /**
     * @var string
     *
     * @ORM\Column(name="instagram", type="string" , length=255,nullable=true)
     */
    protected $instagram;
    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="string" , length=255,nullable=true)
     */
    protected $facebook;
    /**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string" , length=255,nullable=true)
     */
    protected $twitter;

    /**
     * @var string
     *
     * @ORM\Column(name="imageUser", type="string", length=255, nullable=true)
     */
    private $imageuser;

    /**
     * @return Date
     */
    public function getDateNaissance()
    {
        return $this->date_naissance;
    }

    /**
     * @param Date $date_naissance
     */
    public function setDateNaissance($date_naissance)
    {
        $this->date_naissance = $date_naissance;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getImageuser()
    {
        return $this->imageuser;
    }

    /**
     * @param string $imageuser
     */
    public function setImageuser($imageuser)
    {
        $this->imageuser = $imageuser;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * @param string $pays
     */
    public function setPays($pays)
    {
        $this->pays = $pays;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param string $ville
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
    }

    /**
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param string $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * @return string
     */
    public function getRelegion()
    {
        return $this->relegion;
    }

    /**
     * @param string $relegion
     */
    public function setRelegion($relegion)
    {
        $this->relegion = $relegion;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->Gender;
    }

    /**
     * @param string $Gender
     */
    public function setGender($Gender)
    {
        $this->Gender = $Gender;
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param string $facebook
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return string
     */
    public function getInstagram()
    {
        return $this->instagram;
    }

    /**
     * @param string $instagram
     */
    public function setInstagram($instagram)
    {
        $this->instagram = $instagram;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * @param string $twitter
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

}