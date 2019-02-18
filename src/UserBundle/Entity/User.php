<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Mgilet\NotificationBundle\Annotation\Notifiable;
use Mgilet\NotificationBundle\NotifiableInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use FOS\MessageBundle\Model\ParticipantInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @Notifiable(name="fos_user")
 * @Vich\Uploadable
 */
class User extends BaseUser implements NotifiableInterface, ParticipantInterface
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
        $this->interets = new ArrayCollection();
        $this->receivedDemandes = new ArrayCollection();
        $this->sendedDemandes = new ArrayCollection();
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
     * @ORM\Column(name="image",type="string", length=255,nullable=true)
     * @var string
     */
    private $image = "fb.jpg";
    /**
     * @Vich\UploadableField(mapping="profil_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     * @var \DateTime
     */
    private $updatedAt;


    /**
     * @ORM\OneToMany(targetEntity="RelationBundle\Entity\Relation", mappedBy="acceptor")
     */
    private $acceptors;


    /**
     * @ORM\OneToMany(targetEntity="RelationBundle\Entity\Relation", mappedBy="requester")
     */
    private $requesters;

    /**
     * @ORM\OneToMany(targetEntity="RelationBundle\Entity\Demande", mappedBy="sender")
     */
    private $sendedDemandes;


    /**
     * @ORM\OneToMany(targetEntity="RelationBundle\Entity\Demande", mappedBy="receiver")
     */
    private $receivedDemandes;

    /**
     * @return mixed
     */
    public function getAcceptors()
    {
        return $this->acceptors;
    }

    /**
     * @param mixed $acceptors
     */
    public function setAcceptors($acceptors)
    {
        $this->acceptors = $acceptors;
    }

    /**
     * @return mixed
     */
    public function getRequesters()
    {
        return $this->requesters;
    }

    /**
     * @param mixed $requesters
     */
    public function setRequesters($requesters)
    {
        $this->requesters = $requesters;
    }

    /**
     * @return mixed
     */
    public function getReceivedDemandes()
    {
        return $this->receivedDemandes;
    }

    /**
     * @param mixed $receivedDemandes
     */
    public function setReceivedDemandes($receivedDemandes)
    {
        $this->receivedDemandes = $receivedDemandes;
    }

    /**
     * @return mixed
     */
    public function getSendedDemandes()
    {
        return $this->sendedDemandes;
    }

    /**
     * @param mixed $sendedDemandes
     */
    public function setSendedDemandes($sendedDemandes)
    {
        $this->sendedDemandes = $sendedDemandes;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
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

    public function getImageFile()
    {
        return $this->imageFile;
    }


    /**
     * @return Date
     */




    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\CentreInteret", mappedBy="user")
     */
    private $interets;

    /**
     * @return mixed
     */
    public function getInterets()
    {
        return $this->interets;
    }

    /**
     * @param mixed $interets
     */
    public function setInterets($interets)
    {
        $this->interets = $interets;
    }

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