<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\DowntimePlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DowntimePlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method DowntimePlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method DowntimePlace[]    findAll()
 * @method DowntimePlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimePlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DowntimePlace::class);
    }

    // /**
    //  * @return DowntimePlace[] Returns an array of DowntimePlace objects
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
    public function findOneBySomeField($value): ?DowntimePlace
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
