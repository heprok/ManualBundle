<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\DowntimeGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DowntimeGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method DowntimeGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method DowntimeGroup[]    findAll()
 * @method DowntimeGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimeGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DowntimeGroup::class);
    }

    // /**
    //  * @return DowntimeGroup[] Returns an array of DowntimeGroup objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DowntimeGroup
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
