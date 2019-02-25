<?php

namespace UserBundle\Repository;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCommentsForBlog($blogId, $approved = true)
    {
        $qb = $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.blog = :blog_id')
            ->addOrderBy('c.created')
            ->setParameter('blog_id', $blogId);

        if (false === is_null($approved))
            $qb->andWhere('c.approved = :approved')
                ->setParameter('approved', $approved);

        return $qb->getQuery()
            ->getResult();
    }
}
