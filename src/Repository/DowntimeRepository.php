<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ReportBundle\Entity\BaseEntity;
use Tlc\ManualBundle\Entity\Shift;
use DatePeriod;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Downtime|null find($id, $lockMode = null, $lockVersion = null)
 * @method Downtime|null findOneBy(array $criteria, array $orderBy = null)
 * @method Downtime[]    findAll()
 * @method Downtime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DowntimeRepository extends ServiceEntityRepository
{
    protected $nameClass;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->nameClass);
    }

    /**
     * Подготавливает запрос для периода
     *
     * @param DatePeriod $period
     * @return QueryBuilder
     */
    private function getQueryFromPeriod(DatePeriod $period, array $sqlWhere = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('d')
            ->where('lower(d.period) BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->getEndDate()->format(DATE_ATOM))
            ->orderBy('d.drecTimestampKey', 'ASC');

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
     * @return Downtime
     */
    public function getLastDowntime()
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.period', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Downtime[] Returns an array of Downtime objects
     */
    public function getDowntimesByShift(Shift $shift)
    {
        return $this->findByPeriod($shift->getPeriod());
    }

    public function getTotalDowntimeByPeriod(DatePeriod $period, array $sqlWhere = []): ?string
    {
        $downtmies = $this->findByPeriod($period, $sqlWhere);
        if (!$downtmies)
            return '00:00:00';

        $durationTime = new DateTime('00:00');
        foreach ($downtmies as $downtime) {
            if ($downtime->getFinish())
                $durationTime->add($downtime->getDurationInterval());
        }
        $durationTime = date_diff(new DateTime('00:00'), $durationTime,  true);
        return BaseEntity::intervalToString($durationTime);
    }

    /**
     * @return Downtime[] Returns an array of Downtime objects
     */
    public function findByPeriod(DatePeriod $period, array $sqlWhere = [])
    {
        return $this->getQueryFromPeriod($period, $sqlWhere)
            ->getQuery()
            ->getResult();
    }

    /*
    protected function findOneBySomeField($value): ?Downtime
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
