<?php

namespace HotesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="commentaire_hote")
 * @ORM\Entity
 */
class CommentaireHote
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
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="HotesBundle\Entity\MaisonsHotes")
     * @ORM\JoinColumn(name="maisons_hotes_id ",referencedColumnName="id",onDelete="cascade")
     */
    private $hote;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publishdate", type="datetime",nullable=true)
     */
    private $publishdate;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255,nullable=true)
     */
    private $content;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getHote()
    {
        return $this->hote;
    }

    /**
     * @param mixed $hote
     */
    public function setHote($hote)
    {
        $this->hote = $hote;
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
     * @return \DateTime
     */
    public function getPublishdate()
    {
        return $this->publishdate;
    }

    /**
     * @param \DateTime $publishdate
     */
    public function setPublishdate($publishdate)
    {
        $this->publishdate = $publishdate;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }


}