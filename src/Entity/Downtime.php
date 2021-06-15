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
use phpDocumentor\Reflection\Location;
use Tlc\ReportBundle\Entity\BaseEntity;

#[ORM\HasLifecycleCallbacks]
//#[ORM\Entity(repositoryClass: DowntimeRepository::class)]
// #[
//     ApiResource(
//         collectionOperations: ["get"],
//         itemOperations: ["get"],
//         normalizationContext: ["groups" => ["downtime:read"]],
//         denormalizationContext: ["groups" => ["downtime:write"]]
//     )
// ]
#[ApiFilter(DateFilter::class, properties: ["startTimestampKey"])]
#[ORM\MappedSuperclass()]
class Downtime
{
    protected DatePeriod $period;
    
    #[ORM\Id]
    #[ORM\Column(name: "period", type: "tstzrange", options: ["comment" => "Время начала простоя"])]
    #[ApiProperty(identifier: true)]
    #[Groups(["downtime:read"])]
    protected $startTimestampKey;

    public function getStartTimestampKey(): ?int
    {
        dd(2);
        return strtotime($this->period->start->format(DATE_ATOM));
    }

    #[Groups(["downtime:read"])]
    public function getStart(): ?string
    {
        dd(2);
        return $this->period->start->format(BaseEntity::DATE_FORMAT_DB);
    }

    public function getPeriod(): DatePeriod
    {
        return $this->period;
    }

    public function setPeriod(\DatePeriod $period): self
    {
        $this->period = $period;
        $this->startTimestampKey = BaseEntity::stringFromPeriod($period);
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
        if (isset($this->period))
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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function syncDrecTostartTimestampKey(LifecycleEventArgs $event)
    {
        dd(1);
        $entityManager = $event->getEntityManager();
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        dump($this->startTimestampKey);
        $this->startTimestampKey = $this->drec->format($platform->getDateTimeFormatString());
        dump($this->startTimestampKey);
    }

    #[ORM\PostLoad]
    public function syncstartTimestampKeyToDrec(LifecycleEventArgs $event)
    {
        dd(1);
        $entityManager = $event->getEntityManager();
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        dump($this->startTimestampKey);
        $this->drec = DateTime::createFromFormat($platform->getDateTimeTzFormatString(), $this->startTimestampKey) ?:
            \DateTime::createFromFormat($platform->getDateTimeFormatString(), $this->startTimestampKey);
    }
}
