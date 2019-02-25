<?php

namespace MusicBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Songs
 *
 * @ORM\Table(name="songs")
 * @ORM\Entity(repositoryClass="MusicBundle\Repository\SongsRepository")
 */
class Songs
{
    public function __construct()
    {
        $this->playlist = new ArrayCollection();
    }

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
     * @var string
     *
     * @ORM\Column(name="creptedname", type="string", length=255)
     */
    private $cname;

    /**
     * @var string
     *
     * @ORM\Column(name="artist", type="string", length=255)
     */
    private $artist;

    /**
     * @var string
     *
     * @ORM\Column(name="`length`", type="smallint", options={"default": 123, "unsigned": true})
     */
    private $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addDate", type="datetime")
     */
    private $addDate;


    /**
     * @ORM\ManyToMany(targetEntity="Playlist", mappedBy="songs")
     *
     */
    private $playlist;

    /**
     * @return mixed
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * @param mixed $playlist
     */
    public function setPlaylist($playlist): void
    {
        $this->playlist = $playlist;
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
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param string $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return \DateTime
     */
    public function getAddDate()
    {
        return $this->addDate;
    }

    /**
     * @param \DateTime $addDate
     */
    public function setAddDate($addDate)
    {
        $this->addDate = $addDate;
    }


    /**
     * @return string
     */
    public function getCname()
    {
        return $this->cname;
    }

    /**
     * @param string $cname
     */
    public function setCname($cname)
    {
        $this->cname = $cname;
    }

}

