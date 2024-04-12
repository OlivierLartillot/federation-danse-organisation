<?php

namespace App\Entity;

use App\Repository\SeasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeasonRepository::class)]
class Season
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $name = null;

    #[ORM\Column]
    private ?bool $isCurrentSeason = null;

    #[ORM\OneToMany(targetEntity: Championship::class, mappedBy: 'season')]
    private Collection $championships;

    #[ORM\Column]
    private ?bool $isArchived = null;

    #[ORM\OneToMany(targetEntity: Licence::class, mappedBy: 'season')]
    private Collection $licences;

    #[ORM\Column]
    private ?bool $modifiedValidatedLicence = null;

    public function __construct()
    {
        $this->championships = new ArrayCollection();
        $this->isArchived = false;
        $this->licences = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

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

    public function isCurrentSeason(): ?bool
    {
        return $this->isCurrentSeason;
    }

    public function setIsCurrentSeason(bool $isCurrentSeason): static
    {
        $this->isCurrentSeason = $isCurrentSeason;

        return $this;
    }

    /**
     * @return Collection<int, Championship>
     */
    public function getChampionships(): Collection
    {
        return $this->championships;
    }

    public function addChampionship(Championship $championship): static
    {
        if (!$this->championships->contains($championship)) {
            $this->championships->add($championship);
            $championship->setSeason($this);
        }

        return $this;
    }

    public function removeChampionship(Championship $championship): static
    {
        if ($this->championships->removeElement($championship)) {
            // set the owning side to null (unless already changed)
            if ($championship->getSeason() === $this) {
                $championship->setSeason(null);
            }
        }

        return $this;
    }

    public function isArchived(): ?bool
    {
        return $this->isArchived;
    }

    public function setIsArchived(bool $isArchived): static
    {
        $this->isArchived = $isArchived;

        return $this;
    }

    /**
     * @return Collection<int, Licence>
     */
    public function getLicences(): Collection
    {
        return $this->licences;
    }

    public function addLicence(Licence $licence): static
    {
        if (!$this->licences->contains($licence)) {
            $this->licences->add($licence);
            $licence->setSeason($this);
        }

        return $this;
    }

    public function removeLicence(Licence $licence): static
    {
        if ($this->licences->removeElement($licence)) {
            // set the owning side to null (unless already changed)
            if ($licence->getSeason() === $this) {
                $licence->setSeason(null);
            }
        }

        return $this;
    }

    public function isModifiedValidatedLicence(): ?bool
    {
        return $this->modifiedValidatedLicence;
    }

    public function setModifiedValidatedLicence(bool $modifiedValidatedLicence): static
    {
        $this->modifiedValidatedLicence = $modifiedValidatedLicence;

        return $this;
    }
}
