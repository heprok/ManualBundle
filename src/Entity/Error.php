<?php

declare(strict_types=1);

namespace Tlc\ManualBundle\Entity;

use Tlc\ManualBundle\Repository\ErrorRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

//#[ORM\Entity(repositoryClass: ErrorRepository::class)]
// #[
//     ApiResource(
//         collectionOperations: ["get", "post"],
//         itemOperations: ["get", "put"],
//         normalizationContext: ["groups" => ["error:read"]],
//         denormalizationContext: ["groups" => ["error:write"]]
//     )
// ]
#[ORM\MappedSuperclass()]
class Error
{
    #[ORM\Id]
    #[ORM\Column(type: "smallint", options: ["comment" => "Код ошибки"])]
    #[Groups(["error:read", "error:write"])]
    protected int $id;

    #[ORM\Column(type: "string", length: 128, options: ["comment" => "Текст ошибки"])]
    #[Groups(["error:read", "error:write"])]
    protected string $text;

    public function __construct(int $id, string $text)
    {
        $this->id = $id;
        $this->text = $text;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}
