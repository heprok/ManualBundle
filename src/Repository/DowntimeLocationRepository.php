<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\DowntimeLocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DowntimeLocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method DowntimeLocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method DowntimeLocation[]    findAll()
 * @method DowntimeLocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimeLocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DowntimeLocation::class);
    }

    // /**
    //  * @return DowntimeLocation[] Returns an array of DowntimeLocation objects
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
    public function findOneBySomeField($value): ?DowntimeLocation
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
