<?php

namespace MusicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Entity\File;


/**
 * Playlist
 *
 * @ORM\Table(name="playlist")
 * @ORM\Entity(repositoryClass="MusicBundle\Repository\PlaylistRepository")
 */
class Playlist
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createDate", type="datetime")
     */
    private $dateCreation;

    /**
     * @ORM\ManyToMany(targetEntity="Songs", inversedBy="playlist")
     *
     * @var ArrayCollection
     *
     * @Assert\NotBlank(message="add an mp3 song")
     * @Assert\File(mimeTypes={ "audio/mpeg" })
     */
    private $songs;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="owner",referencedColumnName="id")
     *
     */
    private $owner;

    /**
     * @ORM\Column(type="array")
     */
    private $paths;

    /**
     * @return mixed
     */
    public function getPaths()
    {
        return $this->paths;
    }

    /**
     * @param mixed $paths
     */
    public function setPaths($paths): void
    {
        $this->paths = $paths;
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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * @param \DateTime $dateCreation
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;
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
     * @return mixed
     */
    public function getSongs()
    {
        return $this->songs;
    }

    /**
     *
     * @param mixed $songs
     */
    public function setSongs($songs): void
    {
        $this->songs = $songs;
    }


    /**
     * Add song
     *
     * @param \MusicBundle\Entity\Songs $song
     *
     * @return Playlist
     */
    public function addSongs(\MusicBundle\Entity\Songs $song)
    {
        // Bidirectional Ownership
        $song->setPlaylist($this);

        $this->songs[] = $song;

        return $this;
    }
//
//    /**
//     * Remove song
//     *
//     * @param \MusicBundle\Entity\Songs $song
//     */
//    public function removeDocument(\MusicBundle\Entity\Songs $song)
//    {
//        $this->songs->removeElement($song);
//    }
//
//    /**
//     * Get songs
//     *
//     * @return \Doctrine\Common\Collections\Collection
//     */
//    public function getSongs()
//    {
//        return $this->songs;
//    }
//
//    /**
//     * Constructor
//     */
//    public function __construct()
//    {
//        $this->songs = new \Doctrine\Common\Collections\ArrayCollection();
//    }
}

