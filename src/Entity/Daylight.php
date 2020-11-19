<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use App\Repository\DaylightRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DaylightRepository::class)
 *@ApiResource(
 *     collectionOperations={"get","post"},
 *     itemOperations={"get"}
 * )
 * @ApiFilter(
 *     DateFilter::class,
 *     properties={"date"}
 * )
 */
class Daylight
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="date")
     */
    private ?\DateTimeInterface $date;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $sunrise;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $sunset;

    /**
     * @ORM\ManyToOne(targetEntity=City::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?City $city;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getSunrise(): ?\DateTimeInterface
    {
        return $this->sunrise;
    }

    public function setSunrise(\DateTimeInterface $sunrise): self
    {
        $this->sunrise = $sunrise;

        return $this;
    }

    public function getSunset(): ?\DateTimeInterface
    {
        return $this->sunset;
    }

    public function setSunset(\DateTimeInterface $sunset): self
    {
        $this->sunset = $sunset;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
