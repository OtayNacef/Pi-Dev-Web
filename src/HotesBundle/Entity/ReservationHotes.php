<?php
/**
 * Created by PhpStorm.
 * User: Bhs Nada
 * Date: 09/02/2019
 * Time: 11:15 AM
 */

namespace HotesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="reservation_hotes")
 */
class ReservationHotes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $numero_reservation;
    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $date_debut;
    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $date_fin;
    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $nb_personne;
    /**
     * @ORM\Column(type="integer",length=150, nullable=true)
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity="MaisonsHotes")
     * @ORM\JoinColumn(name="maisons_hotes_id",referencedColumnName="id",onDelete="cascade")
     */
    private $MaisonsHotes;
    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id",referencedColumnName="id")
     */
    private $user;

    /**
     * @return mixed
     */
    public function getNumeroReservation()
    {
        return $this->numero_reservation;
    }

    /**
     * @param mixed $numero_reservation
     */
    public function setNumeroReservation($numero_reservation)
    {
        $this->numero_reservation = $numero_reservation;
    }

    /**
     * @return mixed
     */
    public function getDateDebut()
    {
        return $this->date_debut;
    }

    /**
     * @param mixed $date_debut
     */
    public function setDateDebut($date_debut)
    {
        $this->date_debut = $date_debut;
    }

    /**
     * @return mixed
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }

    /**
     * @param mixed $date_fin
     */
    public function setDateFin($date_fin)
    {
        $this->date_fin = $date_fin;
    }

    /**
     * @return mixed
     */
    public function getNbPersonne()
    {
        return $this->nb_personne;
    }

    /**
     * @param mixed $nb_personne
     */
    public function setNbPersonne($nb_personne)
    {
        $this->nb_personne = $nb_personne;
    }

    /**
     * @return mixed
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * @param mixed $prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    }

    /**
     * @return mixed
     */
    public function getMaisonsHotes()
    {
        return $this->MaisonsHotes;
    }

    /**
     * @param mixed $MaisonsHotes
     */
    public function setMaisonsHotes($MaisonsHotes)
    {
        $this->MaisonsHotes = $MaisonsHotes;
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


}