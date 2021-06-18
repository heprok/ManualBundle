<?php

namespace Tlc\ManualBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Tlc\ManualBundle\Filter\DateFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use DoctrineExtensions\Types\DatePeriodRange;
use Tlc\ReportBundle\Entity\BaseEntity;

#[ApiFilter(DateFilter::class, properties: ['period'])]
#[ORM\MappedSuperclass()]
class Shift
{
    #[ORM\Id]
    #[ORM\Column(name: "period", type: "tstzrange", options: ["comment" => "Время начала смены"])]
    #[ApiProperty(identifier: true)]
    #[Groups(["shift:read"])]
    protected DatePeriodRange $period;

    #[ORM\Column(type: "smallint", options: ["comment" => "Номер смены"])]
    #[Groups(["shift:read"])]
    protected $number;

    // public function getStartTimestampKey(): ?int
    // {
    //     // dump($this->period->start);
    //     return strtotime($this->period->start->format(DATE_ATOM));
    // }

    #[Groups(["shift:read"])]
    public function getStart(): ?string
    {
        return $this->period->start->format(BaseEntity::DATE_FORMAT_DB);
    }

    #[Groups(["shift:read"])]
    public function getStartTime(): ?string
    {
        return $this->period->start->format(BaseEntity::TIME_FOR_FRONT);
    }

    #[Groups(["shift:read"])]
    public function getEndTime(): ?string
    {
        return $this->period->end ? $this->period->end->format(BaseEntity::TIME_FOR_FRONT) : 'В работе';
    }

    public function getStartShift(): ?string
    {
        return $this->period->start->format(BaseEntity::DATETIME_FOR_FRONT);
    }

    public function getStopShift(): ?string
    {
        return $this->period->end ? $this->period->end->format(BaseEntity::DATETIME_FOR_FRONT) : 'В работе';
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->period->end;
    }

    public function setStartDate(\DateTimeInterface $start): self
    {
        $this->period->start = $start;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getStopDate(): ?\DateTimeInterface
    {
        return $this->period->end;
    }

    public function setStopDate(?\DateTimeInterface $stop): self
    {
        $this->period->end = $stop;

        return $this;
    }

    public function getPeriod(): DatePeriodRange
    {
        return $this->period;
    }

    public function setPeriod(\DatePeriod $period): self
    {
        $this->period = DatePeriodRange::fromPeriod($period);
        // $this->periodKeyString = BaseEntity::stringFromPeriod($period);
        return $this;
    }


    // #[ORM\PrePersist]
    // #[ORM\PreUpdate]
    // public function syncStartToStartTimestampKey(LifecycleEventArgs $event)
    // {
    //     $entityManager = $event->getEntityManager();
    //     $connection = $entityManager->getConnection();
    //     $platform = $connection->getDatabasePlatform();
    //     $this->period->startTimestampKey = $this->period->start->format($platform->getDateTimeFormatString());
    // }

    // #[ORM\PostLoad]
    // public function syncStartTimestampKeyToStart(LifecycleEventArgs $event)
    // {
    //     $entityManager = $event->getEntityManager();
    //     $connection = $entityManager->getConnection();
    //     $platform = $connection->getDatabasePlatform();
    //     $this->period->start = DateTime::createFromFormat($platform->getDateTimeTzFormatString(), $this->period->startTimestampKey) ?:
    //         \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->period->startTimestampKey) ?:
    //         \DateTime::createFromFormat(BaseEntity::DATE_SECOND_TIMEZONE_FORMAT_DB, $this->period->startTimestampKey);
    // }

    public function getPeople(): ?People
    {
        return $this->people;
    }

    public function setPeople(?People $people): self
    {
        $this->people = $people;

        return $this;
    }
}
