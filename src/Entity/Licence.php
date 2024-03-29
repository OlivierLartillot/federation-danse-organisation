<?php

namespace App\Entity;

use App\Repository\LicenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LicenceRepository::class)]
class Licence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'licences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $club = null;

    #[ORM\ManyToOne(inversedBy: 'licences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToMany(targetEntity: Danseur::class, inversedBy: 'licences')]
    private Collection $danseurs;

    #[ORM\ManyToOne(inversedBy: 'licences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Season $season = null;

    #[ORM\Column]
    private ?int $dossard = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $status = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    #[ORM\OneToMany(targetEntity: LicenceComment::class, mappedBy: 'licence')]
    private Collection $licenceComments;


    public function __construct()
    {
        $this->danseurs = new ArrayCollection();
        $this->licenceComments = new ArrayCollection();
        $this->status = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): static
    {
        $this->club = $club;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Danseur>
     */
    public function getDanseurs(): Collection
    {
        return $this->danseurs;
    }

    public function addDanseur(Danseur $danseur): static
    {
        if (!$this->danseurs->contains($danseur)) {
            $this->danseurs->add($danseur);
        }

        return $this;
    }

    public function removeDanseur(Danseur $danseur): static
    {
        $this->danseurs->removeElement($danseur);

        return $this;
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

    public function getDossard(): ?int
    {
        return $this->dossard;
    }

    public function setDossard(int $dossard): static
    {
        $this->dossard = $dossard;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return Collection<int, LicenceComment>
     */
    public function getLicenceComments(): Collection
    {
        return $this->licenceComments;
    }

    public function addLicenceComment(LicenceComment $licenceComment): static
    {
        if (!$this->licenceComments->contains($licenceComment)) {
            $this->licenceComments->add($licenceComment);
            $licenceComment->setLicence($this);
        }

        return $this;
    }

    public function removeLicenceComment(LicenceComment $licenceComment): static
    {
        if ($this->licenceComments->removeElement($licenceComment)) {
            // set the owning side to null (unless already changed)
            if ($licenceComment->getLicence() === $this) {
                $licenceComment->setLicence(null);
            }
        }

        return $this;
    }
}
