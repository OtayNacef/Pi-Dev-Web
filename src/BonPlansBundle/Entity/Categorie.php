<?php
/**
 * Created by PhpStorm.
 * User: emmay
 * Date: 02/02/2019
 * Time: 17:54
 */

namespace BonPlansBundle\Entity;
use Doctrine\ORM\Mapping as ORM ;
/**
 * Class Categorie
 * @package BonPlansBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="categorie")
 */

class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private  $id ;
    /**
     * @ORM\Column(type="string",length=50,nullable=false)
     */
    private $type;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


}