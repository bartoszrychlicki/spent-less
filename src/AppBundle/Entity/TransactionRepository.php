<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TransactionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TransactionRepository extends EntityRepository
{
    function findAllOrderedByDate()
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT t FROM AppBundle:Transaction t ORDER BY t.createdAt DESC'
            )
            ->getResult();
    }
}
