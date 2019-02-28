<?php

namespace GroupBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;


/**
 * Groups
 *
 * @ORM\Table(name="groups")
 * @ORM\Entity(repositoryClass="GroupBundle\Repository\GroupsRepository")
 * @Vich\Uploadable
 */
class Groups
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;
    /**
     * @var integer
     *
     * @ORM\Column(name="nbrMembre", type="integer", length=255)
     */
    private $nbrMembre;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_de_creation", type="date")
     */
    private $dateDeCreation;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",inversedBy="groups")
     * @ORM\JoinColumn(name="IdUser",referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $owner;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;



    /**
     * @Vich\UploadableField(mapping="Album", fileNameProperty="image")
     * @var File
     */
    private $imageFile;
    /**
     * @var integer
     *
     * @ORM\Column(name="nbr_signal", type="integer", length=255)
     */
    private $nbrSignal=0;

    /**
     * @return int
     */
    public function getNbrSignal()
    {
        return $this->nbrSignal;
    }

    /**
     * @param int $nbrSignal
     */
    public function setNbrSignal($nbrSignal)
    {
        $this->nbrSignal = $nbrSignal;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $url
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->datePublication = new \DateTime('now');
        }
    }


    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return mixed
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param mixed $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return int
     */
    public function getNbrMembre()
    {
        return $this->nbrMembre;
    }

    /**
     * @param int $nbrMembre
     */
    public function setNbrMembre($nbrMembre)
    {
        $this->nbrMembre = $nbrMembre;
    }

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
     * Set nom
     *
     * @param string $nom
     *
     * @return Groups
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
     * Set description
     *
     * @param string $description
     *
     * @return Groups
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateDeCreation
     *
     * @param \DateTime $dateDeCreation
     *
     * @return Groups
     */
    public function setDateDeCreation($dateDeCreation)
    {
        $this->dateDeCreation = $dateDeCreation;

        return $this;
    }

    /**
     * Get dateDeCreation
     *
     * @return \DateTime
     */
    public function getDateDeCreation()
    {
        return $this->dateDeCreation;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Groups
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }
}

