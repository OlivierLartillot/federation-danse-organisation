<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $minAge = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $maxAge = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nbMin = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $nbMax = null;

    #[ORM\OneToMany(targetEntity: Licence::class, mappedBy: 'category')]
    private Collection $licences;

    private ?string $categorieDescriptionText;

    #[ORM\Column(nullable: false)]
    private ?bool $archived = null;

    public function __construct()
    {
        $this->licences = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getCategorieDescriptionText()
    {
        return $this->categorieDescriptionText = "{$this->getName()} - {$this->getMinAge()} ans à {$this->getMaxAge()} ans - Min:{$this->getNbMin()} Max:{$this->getNbmax()}"  ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return ucwords(strtolower($this->name));
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getMinAge(): ?int
    {
        return $this->minAge;
    }

    public function setMinAge(int $minAge): static
    {
        $this->minAge = $minAge;

        return $this;
    }

    public function getMaxAge(): ?int
    {
        return $this->maxAge;
    }

    public function setMaxAge(int $maxAge): static
    {
        $this->maxAge = $maxAge;

        return $this;
    }

    public function getNbMin(): ?int
    {
        return $this->nbMin;
    }

    public function setNbMin(int $nbMin): static
    {
        $this->nbMin = $nbMin;

        return $this;
    }

    public function getNbMax(): ?int
    {
        return $this->nbMax;
    }

    public function setNbMax(int $nbMax): static
    {
        $this->nbMax = $nbMax;

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
            $licence->setCategory($this);
        }

        return $this;
    }

    public function removeLicence(Licence $licence): static
    {
        if ($this->licences->removeElement($licence)) {
            // set the owning side to null (unless already changed)
            if ($licence->getCategory() === $this) {
                $licence->setCategory(null);
            }
        }

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

}
