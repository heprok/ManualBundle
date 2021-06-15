<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\EventSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EventSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventSource[]    findAll()
 * @method EventSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventSource::class);
    }

    // /**
    //  * @return EventSource[] Returns an array of EventSource objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventSource
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
