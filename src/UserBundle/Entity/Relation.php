<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relation
 *
 * @ORM\Table(name="relation")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\RelationRepository")
 */
class Relation
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateRelation", type="datetime")
     */
    private $dateRelation;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",inversedBy="requesters")
     * @ORM\JoinColumn(name="requester",referencedColumnName="id")
     *
     */
    private $requester;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User",inversedBy="acceptors")
     * @ORM\JoinColumn(name="acceptor",referencedColumnName="id")
     *
     */
    private $acceptor;


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
     * Set dateRelation
     *
     * @param \DateTime $dateRelation
     *
     * @return Relation
     */
    public function setDateRelation($dateRelation)
    {
        $this->dateRelation = $dateRelation;

        return $this;
    }

    /**
     * Get dateRelation
     *
     * @return \DateTime
     */
    public function getDateRelation()
    {
        return $this->dateRelation;
    }

    /**
     * @return mixed
     */
    public function getRequester()
    {
        return $this->requester;
    }

    /**
     * @param mixed $requester
     */
    public function setRequester($requester)
    {
        $this->requester = $requester;
    }

    /**
     * @return mixed
     */
    public function getAcceptor()
    {
        return $this->acceptor;
    }

    /**
     * @param mixed $acceptor
     */
    public function setAcceptor($acceptor)
    {
        $this->acceptor = $acceptor;
    }
}

