<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\ActionOperator;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ActionOperator|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActionOperator|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActionOperator[]    findAll()
 * @method ActionOperator[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActionOperatorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActionOperator::class);
    }


    // /**
    //  * @return ActionOperator[] Returns an array of ActionOperator objects
    //  */
    // public function findByExampleField($value)
    // {
    //     return $this->createQueryBuilder('a')
    //         ->andWhere('a.exampleField = :val')
    //         ->setParameter('val', $value)
    //         ->orderBy('a.id', 'ASC')
    //         ->setMaxResults(10)
    //         ->getQuery()
    //         ->getResult()
    //     ;
    // }
    

    /*
    public function findOneBySomeField($value): ?ActionOperator
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
