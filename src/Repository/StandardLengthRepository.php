<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\StandardLength;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StandardLength|null find($id, $lockMode = null, $lockVersion = null)
 * @method StandardLength|null findOneBy(array $criteria, array $orderBy = null)
 * @method StandardLength[]    findAll()
 * @method StandardLength[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StandardLengthRepository extends ServiceEntityRepository
{
    protected $nameClass;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->nameClass);
    }

    // /**
    //  * @return StandardLength[] Returns an array of StandardLength objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StandardLength
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
