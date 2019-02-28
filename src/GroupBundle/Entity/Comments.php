<?php

namespace GroupBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comments
 *
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="GroupBundle\Repository\CommentsRepository")
 */
class Comments
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
     * @ORM\JoinColumn(name="IdUser",referencedColumnName="id")
     *
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="GroupBundle\Entity\PublicationGroup")
     * @ORM\JoinColumn(name="Idpub",referencedColumnName="id",onDelete="CASCADE")
     *
     */
    private $pub;

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
     * @var \DateTime
     *
     * @ORM\Column(name="publishdate", type="datetime")
     */
    private $publishdate;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;


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
     * Set publishdate.
     *
     * @param \DateTime $publishdate
     *
     * @return Comments
     */
    public function setPublishdate($publishdate)
    {
        $this->publishdate = $publishdate;

        return $this;
    }

    /**
     * Get publishdate.
     *
     * @return \DateTime
     */
    public function getPublishdate()
    {
        return $this->publishdate;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Comments
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
}
