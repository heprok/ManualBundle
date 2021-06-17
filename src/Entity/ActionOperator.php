<?php

namespace Tlc\ManualBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\MappedSuperclass()]
class ActionOperator
{
    #[ORM\Id]
    #[ORM\Column(type: "smallint", name: "id")]
    #[Groups(["action_operator:read", "action_operator:write"])]
    protected $code;

    #[ORM\Column(type: "string", length: 128, options: ["comment" => "Название действия"])]

    #[Groups(["action_operator:read", "action_operator:write"])]
    protected $name;

    public function __construct(int $code, string $name)
    {
        $this->code = $code;
        $this->name = $name;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
