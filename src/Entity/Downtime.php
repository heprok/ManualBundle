<?php

namespace Tlc\ManualBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiFilter;
use Tlc\ManualBundle\Filter\DateFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Tlc\ManualBundle\Repository\DowntimeRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;
use DoctrineExtensions\Types\DatePeriodRange;
use phpDocumentor\Reflection\Location;
use Salamek\DateRange;
use Tlc\ReportBundle\Entity\BaseEntity;

//#[ORM\Entity(repositoryClass: DowntimeRepository::class)]
// #[
//     ApiResource(
//         collectionOperations: ["get"],
//         itemOperations: ["get"],
//         normalizationContext: ["groups" => ["downtime:read"]],
//         denormalizationContext: ["groups" => ["downtime:write"]]
//     )
// ]
#[ApiFilter(DateFilter::class, properties: ["period"])]
#[ORM\MappedSuperclass()]
class Downtime
{
    #[ORM\Id]
    #[ORM\Column(name: "period", type: "tstzrange", options: ["comment" => "Время начала простоя"])]
    #[Groups(["downtime:read"])]
    #[ApiProperty(identifier: true)]
    protected DatePeriodRange $period;
    
    // protected string $periodKeyString;

    // public function getPeriodKeyString(): ?int
    // {
    //     return strtotime($this->period->start->format(DATE_ATOM));
    // }

    #[Groups(["downtime:read"])]
    public function getStart(): ?string
    {
        return $this->period->start->format(BaseEntity::DATE_FORMAT_DB);
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

    public function getCause(): ?DowntimeCause
    {
        return $this->cause;
    }

    public function setCause(?DowntimeCause $cause): self
    {
        $this->cause = $cause;

        return $this;
    }

    public function getPlace(): ?DowntimePlace
    {
        return $this->place;
    }

    public function setPlace(?DowntimePlace $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getFinish(): ?\DateTimeInterface
    {
        return $this->period->end;
    }

    public function setFinish(?\DateTimeInterface $finish): self
    {
        $this->period->end = $finish;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->place->getLocation();
    }

    public function getGroup(): ?Group
    {
        return $this->cause->getGroups();
    }

    #[Groups(["downtime:read"])]
    public function getStartTime(): ?string
    {
        return $this->period->start->format(BaseEntity::TIME_FOR_FRONT);
    }



    #[Groups(["downtime:read"])]
    public function getEndTime(): ?string
    {
        if (isset($this->period->end))
            return $this->period->end->format(BaseEntity::TIME_FOR_FRONT);
        else {
            return 'Продолжается';
        }
    }

    #[Groups(["downtime:read"])]
    public function getDurationTime(): ?string
    {
        if (isset($this->period->end))
            return $this->period->end->diff($this->period->start)->format(BaseEntity::INTERVAL_TIME_SECOND_FORMAT);
        else {
            return 'Продолжается';
        }
    }

    public function getDurationInterval(): ?DateInterval
    {
        if (isset($this->period->end)) {
            $dateInterval = $this->period->end->diff($this->period->start);
            $dateInterval->f = 0;
            return $dateInterval;
        } else {
            return null;
        }
    }

    // #[ORM\PrePersist]
    // #[ORM\PreUpdate]
    // public function syncDrecToperiodKeyString(LifecycleEventArgs $event)
    // {
    //     $entityManager = $event->getEntityManager();
    //     $connection = $entityManager->getConnection();
    //     $platform = $connection->getDatabasePlatform();
    //     $this->periodKeyString = $this->period->start->format($platform->getDateTimeFormatString());
    // }

    // #[ORM\PostLoad]
    // public function syncperiodKeyStringToDrec(LifecycleEventArgs $event)
    // {
    //     // $entityManager = $event->getEntityManager();
    //     // $connection = $entityManager->getConnection();
    //     // $platform = $connection->getDatabasePlatform();
    //     // dump($this->periodKeyString);
    //     // if($this->periodKeyString instanceof DatePeriodRange) {
            
    //     $this->period = DatePeriodRange::fromString($this->periodKeyString);
    //     // }
    //     // DateTime::createFromFormat($platform->getDateTimeTzFormatString(), $this->periodKeyString) ?:
    //         // \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->periodKeyString);
    // }
}
