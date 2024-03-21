<?php

namespace App\Entity;

use App\Repository\ChampionshipRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChampionshipRepository::class)]
class Championship
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'championships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Season $season = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $championshipDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $championshipInscriptionsLimitDate = null;

    #[ORM\Column(length: 255)]
    private ?string $place = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $number = null;

    #[ORM\ManyToOne(inversedBy: 'championships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $organizingClub = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeason(): ?Season
    {
        return $this->season;
    }

    public function setSeason(?Season $season): static
    {
        $this->season = $season;

        return $this;
    }

    public function getChampionshipDate(): ?\DateTimeImmutable
    {
        return $this->championshipDate;
    }

    public function setChampionshipDate(\DateTimeImmutable $championshipDate): static
    {
        $this->championshipDate = $championshipDate;

        return $this;
    }

    public function getChampionshipInscriptionsLimitDate(): ?\DateTimeImmutable
    {
        return $this->championshipInscriptionsLimitDate;
    }

    public function setChampionshipInscriptionsLimitDate(\DateTimeImmutable $championshipInscriptionsLimitDate): static
    {
        $this->championshipInscriptionsLimitDate = $championshipInscriptionsLimitDate;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getOrganizingClub(): ?Club
    {
        return $this->organizingClub;
    }

    public function setOrganizingClub(?Club $organizingClub): static
    {
        $this->organizingClub = $organizingClub;

        return $this;
    }
}
