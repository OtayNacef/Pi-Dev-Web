<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Participants
 *
 * @ORM\Table(name="participants")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\ParticipantsRepository")
 */
class Participants
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
     * @ORM\Column(name="confirmation", type="boolean")
     */
    private $confirmation;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="userId",referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $userid;

    /**
     * @ORM\ManyToOne(targetEntity="EventBundle\Entity\Evenement")
     * @ORM\JoinColumn(name="event_id",referencedColumnName="id" ,onDelete="CASCADE")
     *
     */
    private $evenement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_inscrit", type="datetime")
     */
    private $dateInscrit;

    /**
     * @var \String
     *
     * @ORM\Column(name="champs_confirmaton", type="string" , length=255)
     */
    private $champsConfirmation;


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
     * Set confirmation
     *
     * @param boolean $confirmation
     *
     * @return Participants
     */
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    /**
     * Get confirmation
     *
     * @return bool
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * Set userid
     *
     * @param string $userid
     *
     * @return Participants
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;

        return $this;
    }

    /**
     * Get userid
     *
     * @return string
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * Set evenement
     *
     * @param string $evenement
     *
     * @return Participants
     */
    public function setEvenement($evenement)
    {
        $this->evenement = $evenement;

        return $this;
    }

    /**
     * Get evenement
     *
     * @return string
     */
    public function getEvenement()
    {
        return $this->evenement;
    }

    /**
     * Set dateInscrit
     *
     * @param \DateTime $dateInscrit
     *
     * @return Participants
     */
    public function setDateInscrit($dateInscrit)
    {
        $this->dateInscrit = $dateInscrit;

        return $this;
    }

    /**
     * Get dateInscrit
     *
     * @return \DateTime
     */
    public function getDateInscrit()
    {
        return $this->dateInscrit;
    }

    /**
     * @return String
     */
    public function getChampsConfirmation()
    {
        return $this->champsConfirmation;
    }

    /**
     * @param String $champsConfirmation
     */
    public function setChampsConfirmation($champsConfirmation)
    {
        $this->champsConfirmation = $champsConfirmation;
    }

}

