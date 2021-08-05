<?php

namespace Tlc\ManualBundle\Repository;

use Tlc\ManualBundle\Entity\Shift;
use DateInterval;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shift|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shift|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shift[]    findAll()
 * @method Shift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiftRepository extends ServiceEntityRepository
{
    protected $nameClass;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->nameClass);
    }

    /**
     * Подготавливает запрос для периода
     *  где Where если смена не завершена, то берём половину времени прошедшего от начала смены до текущего моента 
     * @param DatePeriod $period
     * @return QueryBuilder
     */
    private function getQueryFromPeriod(DatePeriod $period): QueryBuilder
    {

        return $this->createQueryBuilder('s')
            ->where('CAST(((COALESCE(upper(s.period), now()) - lower(s.period)) / 2 + lower(s.period)) as timestamp) BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->end ? $period->getEndDate()->format(DATE_ATOM) : date(DATE_ATOM))
            ->orderBy('s.period', 'ASC');
    }

    public function getTimeWorkForOperator(DatePeriod $period, int $idOperator, string $format = 'hours')
    {
        return $this->getQueryFromPeriod($period)
            ->select("date_part('$format', sum(age(upper(s.period), lower(s.period)))) as timework")
            ->andWhere('s.people = :idOperator')
            ->setParameter('idOperator', $idOperator)
            ->orderBy('timework')
            ->getQuery()
            ->getResult()[0]['timework'] ?? 0;
    }

    public function getTimeDowntimeForOperator(DatePeriod $period, int $idOperator, string $formatInterval = '%H:%I:%S'): string
    {
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('inter', 'inter');
        $sql = "SELECT sum((
            SELECT
                sum(AGE(upper(d.period), lower(d.period)))
            FROM ds.downtime d
        WHERE
            lower(d.period) BETWEEN lower(s.period) AND upper(s.period)
            AND (d.cause_id NOT IN ( SELECT DISTINCT
                        b.cause_id FROM ds.break_shedule b)
                OR d.cause_id IS NULL))) inter
                FROM ds.shift s
        WHERE (CAST(((COALESCE(upper(s.period), NOW()) - lower(s.period)) / 2 + lower(s.period)) AS timestamp) 
        BETWEEN :start AND :end) AND s.people_id = :people_id";

        $query = $this->getEntityManager()->createNativeQuery($sql, $rsm);

        $query->setParameter('start', $period->getStartDate()->format(DATE_ATOM));
        $query->setParameter('end', $period->end ? $period->getEndDate()->format(DATE_ATOM) : date(DATE_ATOM));
        $query->setParameter('people_id', $idOperator);
        $durationDowntime = $query->getResult()[0]['inter'];

        list($hours, $minutes, $seconds, $milisecond) = sscanf($durationDowntime, '%d:%d:%d.%d');
        $interval = new DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds));

        return $interval->format($formatInterval);
    }
    public function getCountShiftForOperator(DatePeriod $period, int $idOperator): int
    {
        return $this->getQueryFromPeriod($period)
            ->select('count(1) as count')
            ->andWhere('s.people = :idOperator')
            ->setParameter('idOperator', $idOperator)
            ->orderBy('count')
            ->getQuery()
            // ->getOneOrNullResult();
            // ->getSQL();
            ->getResult()[0]['count'] ?? 0;
        // dd($dd);

    }


    /**
     * @return Shift
     */
    public function getLastShift()
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.period', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getPeopleForByPeriod(DatePeriod $period)
    {
        return $this->getQueryFromPeriod($period)
            ->select('p')
            ->leftJoin('App:People', 'p', \Doctrine\ORM\Query\Expr\Join::WITH, 's.people = p.id')
            ->groupBy('p')
            ->orderBy('p.fam')
            ->getQuery()
            ->getResult();
    }
    public function findByPeriodWithPeople(DatePeriod $period, array $idsPeople)
    {
        return $this->getQueryFromPeriod($period)
            ->select('s')
            ->andWhere('s.people IN (:idsPeople)')
            ->setParameter(':idsPeople', $idsPeople)
            ->getQuery()
            ->getResult();
    }
    /**
     * @return Shift[] Returns an array of Shift objects
     */
    public function findByPeriod(DatePeriod $period)
    {
        return $this->getQueryFromPeriod($period)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Shift
     */
    public function getCurrentShift()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('upper(s.period) is null')
            ->orderBy('s.period', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
    // /**
    //  * @return Shift[] Returns an array of Shift objects
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
    public function findOneBySomeField($value): ?Shift
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
