<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PubComment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Blog")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="blog", referencedColumnName="id" ,onDelete="CASCADE") })
     */
    private $blog;

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
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @param mixed $blog
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;
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
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
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