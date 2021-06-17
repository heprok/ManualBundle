<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\Duty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Duty|null find($id, $lockMode = null, $lockVersion = null)
 * @method Duty|null findOneBy(array $criteria, array $orderBy = null)
 * @method Duty[]    findAll()
 * @method Duty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DutyRepository extends ServiceEntityRepository
{
    protected $nameClass;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->nameClass);
    }

    // /**
    //  * @return Duty[] Returns an array of Duty objects
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
    public function findOneBySomeField($value): ?Duty
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
