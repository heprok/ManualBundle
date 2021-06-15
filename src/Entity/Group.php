<?php

namespace Tlc\ManualBundle\Entity;

use Tlc\ManualBundle\Repository\GroupRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

//#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\MappedSuperclass()]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]

    #[Groups(["unload:read"])]
    protected $id;

    #[ORM\Column(type: "boolean")]
    #[Groups(["unload:read"])]
    protected $dry;

    #[ORM\Column(type: "integer")]
    #[Groups(["unload:read"])]
    protected $qualities;

    #[ORM\Column(type: "smallint")]
    #[Groups(["unload:read"])]
    protected $thickness;

    #[ORM\Column(type: "smallint")]
    #[Groups(["unload:read"])]
    protected $width;

    #[ORM\Column(type: "smallint")]
    #[Groups(["unload:read"])]
    protected $min_length;

    #[ORM\Column(type: "smallint")]
    #[Groups(["unload:read"])]
    protected $max_length;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDry(): ?bool
    {
        return $this->dry;
    }

    public function setDry(bool $dry): self
    {
        $this->dry = $dry;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getQualities(): ?int
    {
        return $this->qualities;
    }

    public function setQualities(int $qualities): self
    {
        $this->qualities = $qualities;

        return $this;
    }

    public function getThickness(): ?int
    {
        return $this->thickness;
    }

    public function setThickness(int $thickness): self
    {
        $this->thickness = $thickness;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    #[Groups(["unload:read"])]
    public function getCut(): string
    {
        return $this->thickness . ' × ' . $this->width;
    }

    #[Groups(["unload:read"])]
    public function getIntervalLength(): string
    {
        return $this->min_length !== $this->max_length ?
            $this->min_length . ' - ' .  $this->max_length :
            $this->max_length;
    }

    public function getIntervalLengthInMeter(): string
    {
        return $this->min_length !== $this->max_length ?
            round($this->min_length / 1000, 1) . ' - ' .  round($this->max_length / 1000, 1) . ' м' :
            round($this->max_length / 1000, 1) . ' м';
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getMinLength(): ?int
    {
        return $this->min_length;
    }

    public function setMinLength(int $min_length): self
    {
        $this->min_length = $min_length;

        return $this;
    }

    public function getMaxLength(): ?int
    {
        return $this->max_length;
    }

    public function setMaxLength(int $max_length): self
    {
        $this->max_length = $max_length;

        return $this;
    }
}
