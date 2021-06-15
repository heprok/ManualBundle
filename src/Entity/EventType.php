<?php

namespace Tlc\ManualBundle\Entity;

use Tlc\ManualBundle\Repository\EventTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

//#[ORM\Entity(repositoryClass: EventTypeRepository::class)]
// #[
//     ApiResource(
//         collectionOperations: ["get", "post"],
//         itemOperations: ["get", "put", "delete"],
//         normalizationContext: ["groups" => ["event_type:read"]],
//         denormalizationContext: ["groups" => ["event_type:write"]]
//     )
// ]
#[ORM\MappedSuperclass()]
class EventType
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 1)]
    #[Groups(["event_type:read"])]
    protected $id;

    #[ORM\Column(type: "string", length: 16)]
    #[Groups(["event_type:read", "event:read"])]
    protected $name;

    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name ?? '';
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
