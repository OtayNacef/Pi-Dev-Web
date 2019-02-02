<?php

namespace UserBundle\Repository;

/**
 * RelationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RelationRepository extends \Doctrine\ORM\EntityRepository
{
    public function fetchMembers($user)
    {
        $qb = $this->createQueryBuilder('r');
        //$qb->select("a.requester as user");
        $qb->andWhere("r.acceptor = :u1")->setParameter(":u1",$user);
        $qb->orWhere("r.requester = :u2")->setParameter(":u2",$user);
        return $qb->getQuery()->execute();
    }

    public function checkMembers($user)
    {
        $qb = $this->createQueryBuilder('r');
        $qb->select($qb->expr()->count("r"));
        $qb->andWhere("r.acceptor in (:u1)")->setParameter(":u1",$user);
        $qb->andWhere("r.requester in (:u2)")->setParameter(":u2",$user);
        return $qb->getQuery()->getSingleScalarResult();
    }
}
