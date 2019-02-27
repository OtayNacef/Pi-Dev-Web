<?php
/**
 * Created by PhpStorm.
 * User: Bhs Nada
 * Date: 13/02/2019
 * Time: 12:53 PM
 */

namespace HotesBundle\Repository;


use Doctrine\ORM\EntityRepository;

class HotesRepository extends EntityRepository
{
    public function findEntitiesByString($nom)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT n
                FROM HotesBundle:MaisonsHotes n
                WHERE n.nom LIKE :nom'
            )
            ->setParameter('nom', '%' . $nom . '%')
            ->getResult();
    }

    public function FilterByPays($pays)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT p from HotesBundle:MaisonsHotes p WHERE p.pays LIKE :pays'
        )->setParameter('pays', '%' . $pays . '%')->getResult();
    }

    public function ajaxRecherche($n)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT m FROM HotesBundle:MaisonsHotes m
              
              WHERE
                m.nom like :n")
            ->setParameter('n', $n . '%')
            ->getResult();

    }
}