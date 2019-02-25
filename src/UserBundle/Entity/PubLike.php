<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PubLike
 *
 * @ORM\Table(name="Pub_like")
 */
class PubLike
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
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")})
     */
    private $user;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Publication")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pub", referencedColumnName="id")})
     *
     */
    private $pub;

    /**
     *
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\PubComment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="comment", referencedColumnName="id")})
     *
     */
    private $comment;


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
     * Set user
     *
     * @param integer $user
     *
     * @return Love
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getPub()
    {
        return $this->pub;
    }

    /**
     * @param mixed $pub
     */
    public function setPub($pub)
    {
        $this->pub = $pub;
    }


    /**
     * Set comment
     *
     * @param integer $comment
     *
     * @return PubLike
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return int
     */
    public function getComment()
    {
        return $this->comment;
    }
}
