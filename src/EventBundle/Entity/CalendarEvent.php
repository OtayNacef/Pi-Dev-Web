<?php

namespace EventBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AncaRebeca\FullCalendarBundle\Model\Event;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CalendarEvent
 *
 * @ORM\Table(name="calendar_event")
 * @ORM\Entity(repositoryClass="EventBundle\Repository\CalendarEventRepository")
 */
class CalendarEvent extends Event
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", nullable=true)
     * @Assert\NotBlank()
     */
    private $chargeRecrutement;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length = 2000, nullable = true)
     */
    private $commentaire;


    /**
     * Get allDay
     *
     * @return boolean
     */
    public function getAllDay()
    {
        return $this->allDay;
    }

    /**
     * Get editable
     *
     * @return boolean
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Get startEditable
     *
     * @return boolean
     */
    public function getStartEditable()
    {
        return $this->startEditable;
    }

    /**
     * Get durationEditable
     *
     * @return boolean
     */
    public function getDurationEditable()
    {
        return $this->durationEditable;
    }

    /**
     * Get overlap
     *
     * @return boolean
     */
    public function getOverlap()
    {
        return $this->overlap;
    }

    /**
     * Set customFields
     *
     * @param array $customFields
     *
     * @return CalendarEvent
     */
    public function setCustomFields($customFields)
    {
        $this->customFields = $customFields;

        return $this;
    }

    /**
     * Set chargeRecrutement
     *
     * @param UserBundle\Entity\User $chargeRecrutement
     *
     * @return CalendarEvent
     */
    public function setChargeRecrutement(UserBundle\Entity\User $chargeRecrutement = null)
    {
        $this->chargeRecrutement = $chargeRecrutement;

        return $this;
    }

    /**
     * Get chargeRecrutement
     *
     * @return UserBundle\Entity\User
     */
    public function getChargeRecrutement()
    {
        return $this->chargeRecrutement;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return CalendarEvent
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }
}