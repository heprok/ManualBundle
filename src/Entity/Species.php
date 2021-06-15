<?php

declare(strict_types=1);

namespace Tlc\ManualBundle\Entity;

use Tlc\ManualBundle\Repository\SpeciesRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

// //#[ORM\Entity(repositoryClass: SpeciesRepository::class, readOnly: false)]
// #[ORM\Table(name: 'species', schema: 'dic', options: ['comment' => 'Породы древесины'])]
// #[ORM\InheritanceType('JOINED')]
// #[ORM\DiscriminatorColumn(name: 'discr', type: "string")]
// #[ORM\DiscriminatorMap(['my_chilld' => 'MyChild'])]
#[ORM\MappedSuperclass()]
class Species
{
    #[ORM\Id]
    #[ORM\Column(type: "string", length: 2, options: ["fixed" => "true"])]
    #[Groups(["species:read"])]
    protected string $id;

    #[ORM\Column(type: "string", length: 25, options: ["comment" => "Название"])]
    #[Groups(["species:read", "package:read", "unload:read"])]
    protected string $name;

    #[ORM\Column(type: "boolean", options: ["comment" => "Хвойное"])]
    #[Groups(["species:read", "species:write"])]
    protected bool $fir;

    #[ORM\Column(type: "float", nullable: true, options: ["comment" => "Устойчивость к гниению по DIN EN 350-2"])]
    protected ?float $pers;

    #[ORM\Column(type: "smallint", nullable: true, options: ["comment" => "Твёрдость по шкале Янка"])]
    protected ?int $hard_min;

    #[ORM\Column(type: "smallint", nullable: true, options: ["comment" => ""])]
    protected ?int $hard_max;

    #[ORM\Column(type: "smallint", nullable: true, options: ["comment" => "Плотность кг/м³ при 15% влажности"])]
    protected ?int $dens_min;

    #[ORM\Column(type: "smallint", nullable: true, options: ["comment" => ""])]
    protected ?int $dens_max;


    #[ORM\Column(type: "boolean", options: ["default" => true])]
    #[Groups(["species:read", "species:write"])]
    protected bool $enabled;

    public function __construct(
        string $id,
        string $name,
        bool $fir,
        ?float $pers = null,
        ?int $hard_min = null,
        ?int $hard_max = null,
        ?int $dens_min = null,
        ?int $dens_max = null,
        ?bool $enabled = true
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->fir = $fir;
        $this->pers = $pers;
        $this->hard_min = $hard_min;
        $this->hard_max = $hard_max;
        $this->dens_min = $dens_min;
        $this->dens_max = $dens_max;
        $this->enabled = $enabled;
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getFir(): ?bool
    {
        return $this->fir;
    }

    public function setFir(bool $fir): self
    {
        $this->fir = $fir;

        return $this;
    }

    public function getPers(): ?float
    {
        return $this->pers;
    }

    public function setPers(?float $pers): self
    {
        $this->pers = $pers;

        return $this;
    }

    public function getHardMin(): ?int
    {
        return $this->hard_min;
    }

    public function setHardMin(?int $hard_min): self
    {
        $this->hard_min = $hard_min;

        return $this;
    }

    public function getHardMax(): ?int
    {
        return $this->hard_max;
    }

    public function setHardMax(int $hard_max): self
    {
        $this->hard_max = $hard_max;

        return $this;
    }

    public function getDensMin(): ?int
    {
        return $this->dens_min;
    }

    public function setDensMin(?int $dens_min): self
    {
        $this->dens_min = $dens_min;

        return $this;
    }

    public function getDensMax(): ?int
    {
        return $this->dens_max;
    }

    public function setDensMax(?int $dens_max): self
    {
        $this->dens_max = $dens_max;

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
}
