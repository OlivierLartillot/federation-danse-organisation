<?php

namespace App\Entity;

use App\Repository\InscriptionChampionnatRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionChampionnatRepository::class)]
class InscriptionChampionnat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptionChampionnats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Championship $championnat = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptionChampionnats')]
    private ?Licence $licence = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChampionnat(): ?Championship
    {
        return $this->championnat;
    }

    public function setChampionnat(?Championship $championnat): static
    {
        $this->championnat = $championnat;

        return $this;
    }

    public function getLicence(): ?Licence
    {
        return $this->licence;
    }

    public function setLicence(?Licence $licence): static
    {
        $this->licence = $licence;

        return $this;
    }
}
