<?php

namespace GroupBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Signalgroup
 *
 * @ORM\Table(name="signalgroup")
 * @ORM\Entity(repositoryClass="GroupBundle\Repository\SignalgroupRepository")
 */
class Signalgroup
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
     * @ORM\Column(name="cause", type="string", length=255)
     */
    private $cause;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",inversedBy="signalgroup")
     * @ORM\JoinColumn(name="IdUser",referencedColumnName="id")
     *
     */
    private $owner;
    /**
     * @ORM\ManyToOne(targetEntity="GroupBundle\Entity\Groups",inversedBy="signalgroup")
     * @ORM\JoinColumn(name="IdGroup",referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $group;

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCause()
    {
        return $this->cause;
    }

    /**
     * @param string $cause
     */
    public function setCause($cause)
    {
        $this->cause = $cause;
    }


}
