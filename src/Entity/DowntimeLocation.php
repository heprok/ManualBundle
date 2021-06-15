<?php

namespace Tlc\ManualBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Tlc\ManualBundle\Repository\DowntimeLocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

//#[ORM\Entity(repositoryClass: DowntimeLocationRepository::class)]
// #[
//     ApiResource(
//         collectionOperations: ["get", "post"],
//         itemOperations: ["get", "put"],
//         normalizationContext: ["groups" => ["downtime_location:read"]],
//         denormalizationContext: ["groups" => ["downtime_location:write"]]
//     )
// ]
#[ORM\MappedSuperclass()]
class DowntimeLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "id")]
    #[ApiProperty(identifier: true)]
    #[Groups(["downtime_location:read", "downtime_location:write", "downtime_place:read"])]
    protected int $code;

    #[ORM\Column(type: "string", length: 128, name: "text", options: ["comment" => "Название причины"])]
    #[Groups(["downtime_location:read", "downtime_location:write", "downtime_place:read"])]
    protected string $name;

    #[ORM\Column(type: "boolean", options: ["comment" => "Используется", "default" => "true"])]
    #[Groups(["downtime_location:read", "downtime_location:write"])]
    protected bool $enabled = true;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->downtimePlaces = new ArrayCollection();
        $this->downtimes = new ArrayCollection();
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


    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * @return Collection|DowntimePlace[]
     */
    public function getDowntimePlaces(): Collection
    {
        return $this->downtimePlaces;
    }

    public function addDowntimePlace(DowntimePlace $downtimePlace): self
    {
        if (!$this->downtimePlaces->contains($downtimePlace)) {
            $this->downtimePlaces[] = $downtimePlace;
            $downtimePlace->setLocation($this);
        }

        return $this;
    }

    public function removeDowntimePlace(DowntimePlace $downtimePlace): self
    {
        if ($this->downtimePlaces->removeElement($downtimePlace)) {
            // set the owning side to null (unless already changed)
            if ($downtimePlace->getLocation() === $this) {
                $downtimePlace->setLocation(null);
            }
        }

        return $this;
    }
}
