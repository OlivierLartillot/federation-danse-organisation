<?php

namespace App\Entity;

use App\Repository\OrganizationTeamRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganizationTeamRepository::class)]
class OrganizationTeam
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $name = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $organizationRole = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getOrganizationRole(): ?string
    {
        return $this->organizationRole;
    }

    public function setOrganizationRole(?string $organizationRole): static
    {
        $this->organizationRole = $organizationRole;

        return $this;
    }
}
