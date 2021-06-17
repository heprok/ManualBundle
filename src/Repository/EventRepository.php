<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\Event;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    protected $nameClass;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->nameClass);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
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


    private function getBaseQueryFromPeriod(DatePeriod $period, array $sqlWhere = []) :QueryBuilder
    {

        $qb = $this->createQueryBuilder('e')
            ->andWhere('e.drecTimestampKey BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->getEndDate()->format(DATE_ATOM))
            ->orderBy('e.drecTimestampKey', 'ASC');

        foreach ($sqlWhere as $where) {
            $query = $where->nameTable . $where->id . ' ' . $where->operator . ' ' . $where->value;
            if ($where->logicalOperator == 'AND')
                $qb->andWhere($query);
            else
                $qb->orWhere($query);
        }

        return $qb;
    }
    /**
     * Возращает события с заданным типом и источником
     *
     * @param string[] $type
     * @param string[] $source
     * @return Event[]
     */
    public function findByTypeAndSourceFromPeriod(DatePeriod $period, array $type, array $source, array $sqlWhere = [])
    {
        return $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere)
            ->andWhere('e.type IN( :type )')
            ->andWhere('e.source IN( :source )')
            ->setParameter('type', $type)
            ->setParameter('source', $source)
            ->getQuery()
            ->getResult();
        return $qb;
    }
}
