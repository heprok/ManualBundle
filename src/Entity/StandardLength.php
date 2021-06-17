<?php

declare(strict_types=1);

namespace Tlc\ManualBundle\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use Tlc\ManualBundle\Repository\StandardLengthRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

//#[ORM\Entity(repositoryClass: StandardLengthRepository::class)]
// #[ApiResource(
//     collectionOperations: [
//         'get' => ['method' => 'GET', 'path' => '/lengths'],
//         'post' => ['method' => 'POST', 'path' => '/lengths']
//     ],
//     itemOperations: [
//         'get' => ['method' => 'GET', 'path' => '/lengths/{standard}'],
//         'put' => ['method' => 'PUT', 'path' => '/lengths/{standard}'],
//         'delete' => ['method' => 'DELETE', 'path' => '/lengths/{standard}'],
//     ],
//     normalizationContext: [
//         "groups" => ['standard_length:read']
//     ],
//     denormalizationContext: [
//         'groups' => ['standard_length:write'],
//         'disable_type_enforcement' => true
//     ],
// )]
#[ORM\MappedSuperclass()]
class StandardLength
{
    #[ORM\Id]
    #[ORM\Column(type: "smallint")]
    #[ApiProperty(identifier: true)]
    #[Groups(["standard_length:read", "standard_length:write"])]
    protected int $standard;


    #[ORM\Column(type: "int2range", options: ["comment" => "Диапазон фактических длин, мм"])]
    protected array $range;

    public function __construct(int $standard, array $range)
    {
        $this->standard = $standard;
        $this->range = $range;
    }

    public function getStandard(): ?int
    {
        return $this->standard;
    }

    public function setStandard(int $standard): self
    {
        $this->standard = $standard;

        return $this;
    }

    #[Groups(["standard_length:read"])]
    public function getMinimum(): ?int
    {
        return min($this->range);
    }

    #[Groups(["standard_length:write"])]
    public function setMinimum(int $minimum): self
    {
        $this->range[0] = $minimum;

        return $this;
    }

    #[Groups(["standard_length:read"])]
    public function getMaximum(): ?int
    {
        return max($this->range);
    }

    #[Groups(["standard_length:write"])]
    public function setMaximum(int $maximum): self
    {
        $this->range[1] = $maximum;

        return $this;
    }
}
