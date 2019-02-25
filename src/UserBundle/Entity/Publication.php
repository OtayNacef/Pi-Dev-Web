<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Publication
 *
 * @ORM\Table(name="publication")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\PublicationRepository")
 */
class Publication
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
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePublication", type="datetime")
     */
    private $datePublication;

    /**
     * @var int
     *
     * @ORM\Column(name="repliesnumber", type="bigint" , nullable=true)
     */
    private $repliesnumber;
    /**
     * @var int
     *
     * @ORM\Column(name="likesnumber", type="bigint", nullable=true)
     */
    private $likesnumber;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="IdUser",referencedColumnName="id")
     *
     */
    private $user;
    // ..

    /**
     * @ORM\OneToMany(targetEntity="PubComment", mappedBy="Publication")
     */
    protected $PubComment;

    /**
     * @return int
     */
    public function getRepliesnumber()
    {
        return $this->repliesnumber;
    }

    /**
     * @param int $repliesnumber
     */
    public function setRepliesnumber($repliesnumber)
    {
        $this->repliesnumber = $repliesnumber;
    }

    /**
     * @return int
     */
    public function getLikesnumber()
    {
        return $this->likesnumber;
    }

    /**
     * @param int $likesnumber
     */
    public function setLikesnumber($likesnumber)
    {
        $this->likesnumber = $likesnumber;
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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Publication
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     *
     * @return Publication
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $User
     */
    public function setUser($User)
    {
        $this->user = $User;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    public function __construct()
    {
        $this->PubComment = new ArrayCollection();
    }


    /**
     * Add pubComment.
     *
     * @param \UserBundle\Entity\PubComment $pubComment
     *
     * @return Publication
     */
    public function addPubComment(\UserBundle\Entity\PubComment $pubComment)
    {
        $this->PubComment[] = $pubComment;

        return $this;
    }

    /**
     * Remove pubComment.
     *
     * @param \UserBundle\Entity\PubComment $pubComment
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePubComment(\UserBundle\Entity\PubComment $pubComment)
    {
        return $this->PubComment->removeElement($pubComment);
    }

    /**
     * Get pubComment.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPubComment()
    {
        return $this->PubComment;
    }
}
