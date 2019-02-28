<?php

namespace GroupBundle\Repository;

/**
 * GroupsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupsRepository extends \Doctrine\ORM\EntityRepository
{
    public function findDemandeGroup($id)
    {

        return $this->getEntityManager()
            ->createQuery("SELECT e FROM GroupBundle:GroupsMembers e 
              
              WHERE
                e.groups=:id and e.confirmation=false ")
            ->setParameter('id', $id)
            ->getResult();
    }

    public function findEntitiesByString($str)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT g
                FROM GroupBundle:Groups g
                WHERE g.nom LIKE :str'
            )
            ->setParameter('str', '%' . $str . '%')
            ->getResult();
    }



}
