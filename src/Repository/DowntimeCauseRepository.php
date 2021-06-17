<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\DowntimeCause;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DowntimeCause|null find($id, $lockMode = null, $lockVersion = null)
 * @method DowntimeCause|null findOneBy(array $criteria, array $orderBy = null)
 * @method DowntimeCause[]    findAll()
 * @method DowntimeCause[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimeCauseRepository extends ServiceEntityRepository
{
    protected $nameClass;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->nameClass);
    }

    // /**
    //  * @return DowntimeCause[] Returns an array of DowntimeCause objects
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
    public function findOneBySomeField($value): ?DowntimeCause
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
