<?php

namespace Tlc\ManualBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Tlc\ManualBundle\Repository\DowntimeGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

//#[ORM\Entity(repositoryClass: DowntimeGroupRepository::class)]
// #[
//     ApiResource(
//         collectionOperations: ["get", "post"],
//         itemOperations: ["get", "put"],
//         normalizationContext: ["groups" => ["downtime_group:read"]],
//         denormalizationContext: ["groups" => ["downtime_group:write"]]
//     )
// ]
#[ORM\MappedSuperclass()]
class DowntimeGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "id")]
    #[ApiProperty(identifier: true)]
    #[Groups(["downtime_group:read", "downtime_group:write", "downtime_cause:read"])]
    protected int $code;

    #[ORM\Column(type: "string", length: 128, name: "text", options: ["comment" => "Название причины"])]

    #[Groups(["downtime_group:read", "downtime_group:write", "downtime_cause:read"])]
    protected string $name;

    #[ORM\Column(type: "boolean", options: ["comment" => "Используется", "default" => "true"])]

    #[Groups(["downtime_group:read", "downtime_group:write"])]
    protected bool $enabled = true;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->downtimeCauses = new ArrayCollection();
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
     * Undocumented function
     *
     * @return Collection|DowntimeCause[]
     */
    public function getDowntimeCauses(): Collection
    {
        return $this->downtimeCauses;
    }

    public function addDowntimeCause(DowntimeCause $downtimeCause): self
    {
        if (!$this->downtimeCauses->contains($downtimeCause)) {
            $this->downtimeCauses[] = $downtimeCause;
            $downtimeCause->setGroups($this);
        }

        return $this;
    }

    public function removeDowntimeCause(DowntimeCause $downtimeCause): self
    {
        if ($this->downtimeCauses->removeElement($downtimeCause)) {
            // set the owning side to null (unless already changed)
            if ($downtimeCause->getGroups() === $this) {
                $downtimeCause->setGroups(null);
            }
        }

        return $this;
    }
}
