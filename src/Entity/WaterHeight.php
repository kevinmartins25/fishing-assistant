<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\WaterHeightRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     collectionOperations={"get","post"},
 *     itemOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass=WaterHeightRepository::class)
 */
class WaterHeight
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThanOrEqual(value="0")
     * @Assert\NotBlank()
     */
    private ?float $value;

    /**
     * @ORM\ManyToOne(targetEntity=Station::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private ?Station $station;

    /**
     * @ORM\ManyToOne(targetEntity=River::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private ?River $river;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\LessThan("tomorrow UTC")
     * @Assert\NotBlank()
     */
    private ?\DateTimeInterface $dateTime;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): self
    {
        $this->station = $station;

        return $this;
    }

    public function getRiver(): ?River
    {
        return $this->river;
    }

    public function setRiver(?River $river): self
    {
        $this->river = $river;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }
}
