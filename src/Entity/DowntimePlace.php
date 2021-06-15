<?php

declare(strict_types=1);

namespace Tlc\ManualBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
// use Tlc\ManualBundle\Repository\DowntimePlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

//#[ORM\Entity(repositoryClass: DowntimePlaceRepository::class)]
// #[
//     ApiResource(
//         collectionOperations: ["get", "post"],
//         itemOperations: ["get", "put"],
//         normalizationContext: ["groups" => ["downtime_place:read"]],
//         denormalizationContext: ["groups" => ["downtime_place:write"]]
//     )
// ]
#[ORM\MappedSuperclass()]
class DowntimePlace
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ApiProperty(identifier: true)]
    #[ORM\Column(type: "integer", name: "id")]
    #[Groups(["downtime_place:read", "downtime_place:write"])]
    protected int $code;

    #[ORM\Column(type: "string", length: 128, name: "text", options: ["comment" => "Название места"])]
    #[Groups(["downtime_place:read", "downtime_place:write", "downtime:read", "break_shedule:read"])]
    protected string $name;

    #[ORM\Column(type: "boolean", options: ["comment" => "Используется", "default" => "true"])]
    #[Groups(["downtime_place:read", "downtime_place:write", "downtime:read"])]
    protected bool $enabled = true;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->breakShedules = new ArrayCollection();
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getName(): string
    {
        return $this->name ?? '';
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getLocation(): ?DowntimeLocation
    {
        return $this->location;
    }

    public function setLocation(?DowntimeLocation $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection|BreakShedule[]
     */
    public function getBreakShedules(): Collection
    {
        return $this->breakShedules;
    }

    public function addBreakShedule(BreakShedule $breakShedule): self
    {
        if (!$this->breakShedules->contains($breakShedule)) {
            $this->breakShedules[] = $breakShedule;
            $breakShedule->setPlace($this);
        }

        return $this;
    }

    public function removeBreakShedule(BreakShedule $breakShedule): self
    {
        if ($this->breakShedules->removeElement($breakShedule)) {
            // set the owning side to null (unless already changed)
            if ($breakShedule->getPlace() === $this) {
                $breakShedule->setPlace(null);
            }
        }

        return $this;
    }
}
