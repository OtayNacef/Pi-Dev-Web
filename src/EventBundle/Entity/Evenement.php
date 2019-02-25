<?php

namespace EventBundle\Entity;

use AncaRebeca\FullCalendarBundle\Event\CalendarEvent;
use AncaRebeca\FullCalendarBundle\Model\FullCalendarEvent;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\EvenementRepository")
 * @UniqueEntity("nomEvenement" , message="LE NOM de evenement existe déjà .") // c'est ici que je declare le champs unique
 */
class Evenement
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
     * @ORM\Column(name="nomEvenement", type="string", length=255, nullable=true , unique=true)
     *  * @Assert\NotBlank()
     */
    private $nomEvenement;

    /**
     * @var string
     *
     * @ORM\Column(name="adr", type="string", length=255, nullable=true)
     */
    private $adr;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var float
     *
     * @ORM\Column(name="prix", type="float")
     */
    private $prix;

    /**
     * @var int
     *
     * @ORM\Column(name="nbreplace", type="integer")
     */
    private $nbreplace;

    /**
     * @var int
     *
     * @ORM\Column(name="telresponsable", type="integer")
     */
    private $telresponsable;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="date")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */

    private $image;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFin", type="date")
     */
    private $dateFin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="date")
     */
    private $dateCreation;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="responsable",referencedColumnName="id")
     */
    private $responsable;


    /**
     * Evenement constructor.
     * @param string $adr
     * @param string $description
     * @param \DateTime $dateDebut
     * @param \DateTime $dateFin
     */
//    public function __construct($adr, $description, \DateTime $dateDebut, \DateTime $dateFin)
//    {
//        $this->adr = $adr;
//        $this->description = $description;
//        $this->dateDebut = $dateDebut;
//        $this->dateFin = $dateFin;
//    }

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
     * Set nomEvenement
     *
     * @param string $nomEvenement
     *
     * @return Evenement
     */
    public function setNomEvenement($nomEvenement)
    {
        $this->nomEvenement = $nomEvenement;

        return $this;
    }

    /**
     * Get nomEvenement
     *
     * @return string
     */
    public function getNomEvenement()
    {
        return $this->nomEvenement;
    }

    /**
     * Set adr
     *
     * @param string $adr
     *
     * @return Evenement
     */
    public function setAdr($adr)
    {
        $this->adr = $adr;

        return $this;
    }

    /**
     * Get adr
     *
     * @return string
     */
    public function getAdr()
    {
        return $this->adr;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Evenement
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
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Evenement
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return Evenement
     */
    public function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @return mixed
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * @param mixed $responsable
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return float
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param float $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return int
     */
    public function getNbreplace()
    {
        return $this->nbreplace;
    }

    /**
     * @param int $nbreplace
     */
    public function setNbreplace($nbreplace)
    {
        $this->nbreplace = $nbreplace;
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
     * @return int
     */
    public function getTelresponsable()
    {
        return $this->telresponsable;
    }

    /**
     * @param int $telresponsable
     */
    public function setTelresponsable($telresponsable)
    {
        $this->telresponsable = $telresponsable;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param \DateTime $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
    }

}
