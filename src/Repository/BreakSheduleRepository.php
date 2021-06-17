<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\BreakShedule;
use Tlc\ManualBundle\Entity\Downtime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BreakShedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method BreakShedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method BreakShedule[]    findAll()
 * @method BreakShedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BreakSheduleRepository extends ServiceEntityRepository
{
    protected $nameClass;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->nameClass);
    }

    public function isDowntimeBreak(Downtime $downtime): bool
    {
        $qb = $this->createQueryBuilder('b')
            ->select('')
            ->where('b.place = :place')
            ->andWhere('b.cause = :cause')
            ->setParameter('place', $downtime->getPlace())
            ->setParameter('cause', $downtime->getCause())
            ->getQuery()
            ->setMaxResults(1)
            ->getOneOrNullResult();

        return !is_null($qb);
    }

    // /**
    //  * @return BreakShedule[] Returns an array of BreakShedule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BreakShedule
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
