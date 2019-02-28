<?php

namespace BonPlansBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RatingBonPlan
 *
 * @ORM\Table(name="rating_bon_plan")
 * @ORM\Entity(repositoryClass="BonPlansBundle\Repository\RatingBonPlanRepository")
 */
class RatingBonPlan
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private  $user;
    /**
     * @ORM\ManyToOne(targetEntity="BonPlansBundle\Entity\BonPlan")
     * @ORM\JoinColumn(name="bonplan_id",referencedColumnName="id",onDelete="cascade")
     */
    private  $bonplan;


    /**
     * @ORM\Column(type="boolean")
     */
    private $vote;

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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getBonplan()
    {
        return $this->bonplan;
    }

    /**
     * @param mixed $bonplan
     */
    public function setBonplan($bonplan)
    {
        $this->bonplan = $bonplan;
    }

    /**
     * @return mixed
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * @param mixed $vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;
    }


}
