<?php

namespace GroupBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GroupsMembers
 *
 * @ORM\Table(name="groups_members")
 * @ORM\Entity(repositoryClass="GroupBundle\Repository\GroupsMembersRepository")
 */
class GroupsMembers
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
     * @ORM\JoinColumn(name="Idauthor",referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="GroupBundle\Entity\Groups")
     * @ORM\JoinColumn(name="IdGroup",referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $club;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateInscri", type="datetime")
     */
    private $dateInscri;

    /**
     * @return \DateTime
     */
    public function getDateInscri()
    {
        return $this->dateInscri;
    }

    /**
     * @param \DateTime $dateInscri
     */
    public function setDateInscri($dateInscri)
    {
        $this->dateInscri = $dateInscri;
    }

    /**
     * @return mixed
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * @param mixed $club
     */
    public function setClub($club)
    {
        $this->club = $club;
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
     * Set confirmation.
     *
     * @param bool $confirmation
     *
     * @return GroupsMembers
     */
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    /**
     * Get confirmation.
     *
     * @return bool
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * Set user.
     *
     * @param string $user
     *
     * @return GroupsMembers
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }
}
