<?php

namespace Tlc\ManualBundle\Entity;

use Tlc\ManualBundle\Repository\BreakSheduleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use Tlc\ReportBundle\Entity\BaseEntity;

#[ORM\UniqueConstraint(name: "bredk_shedule_unique", columns: ["start", "stop"])]
// #[ApiResource(
//     collectionOperations: ["get", "post"],
//     itemOperations: ["get", "put"],
//     normalizationContext: ["groups" => ["break_shedule:read"]],
//     denormalizationContext: ["groups" => ["break_shedule:write"]]
// )]
#[ORM\MappedSuperclass()]
class BreakShedule
{

    #[ORM\Id]
    #[ORM\Column(type: "integer", unique: true, options: ["comment" => "Начало перерыва в формате HHMM"])]
    #[ApiProperty(identifier: true)]
    #[Groups(["break_shedule:read", "break_shedule:write", "downtime_place:read"])]
    protected int $start;

    #[ORM\Column(type: "integer", unique: true, options: ["comment" => "Конец перерыва в формате HHMM"])]
    #[Groups(["break_shedule:read", "break_shedule:write", "downtime_place:read"])]
    protected int $stop;

    public function __construct(string $startTime, string $stopTime, DowntimeCause $cause, DowntimePlace $place)
    {
        $this->start = (int)str_replace(':', '', $startTime);
        $this->stop = (int)str_replace(':', '', $stopTime);
        $this->cause = $cause;
        $this->place = $place;
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getStop(): int
    {
        return $this->stop;
    }

    public function setStart(int $start): self
    {
        $this->start = $start;
        return $this;
    }

    public function setStop(int $stop): self
    {
        $this->stop = $stop;
        return $this;
    }

    #[Groups(["break_shedule:read", "downtime_place:read"])]
    public function getStartTime(): string
    {
        $start = BaseEntity::int2time($this->start);
        return $start->format(BaseEntity::INTERVAL_TIME_FORMAT);
    }

    #[Groups(["break_shedule:write"])]
    public function setStartTime(string $start): self
    {
        $this->start = (int)str_replace(':', '', $start);

        return $this;
    }

    #[Groups(["break_shedule:read", "downtime_place:read"])]
    public function getStopTime(): string
    {
        $stop = BaseEntity::int2time($this->stop);
        return $stop->format(BaseEntity::INTERVAL_TIME_FORMAT);
    }

    #[Groups(["break_shedule:write"])]
    public function setStopTime(string $stop): self
    {
        $this->stop = (int)str_replace(':', '', $stop);

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

    public function getCause(): ?DowntimeCause
    {
        return $this->cause;
    }

    public function setCause(?DowntimeCause $cause): self
    {
        $this->cause = $cause;

        return $this;
    }
}
