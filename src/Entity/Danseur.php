<?php

namespace App\Entity;

use App\Repository\DanseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DanseurRepository::class)]
class Danseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $birthday = null;

    #[ORM\ManyToOne(inversedBy: 'danseurs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Club $club = null;

    #[ORM\Column(nullable: true)]
    private ?bool $archived = null;

    #[ORM\ManyToMany(targetEntity: Licence::class, mappedBy: 'danseurs')]
    private Collection $licences;

    #[ORM\Column]
    private ?bool $validated = null;

    #[ORM\OneToOne(mappedBy: 'danseur', cascade: ['persist', 'remove'])]
    private ?DanseurDocuments $danseurDocuments = null;


    public function __construct()
    {
        $this->licences = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getFirstname() .' '. $this->getLastname() ;
    }

    public function getFullName(): ?string
    {
        return $this->getLastname() .' '.  $this->getFirstname();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return ucwords(strtolower($this->firstname));
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return ucwords(strtolower($this->lastname));
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeImmutable $birthday): static
    {
        $this->birthday = $birthday;

        return $this;
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

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

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
            $licence->addDanseur($this);
        }

        return $this;
    }

    public function removeLicence(Licence $licence): static
    {
        if ($this->licences->removeElement($licence)) {
            $licence->removeDanseur($this);
        }

        return $this;
    }

    public function isValidated(): ?bool
    {
        return $this->validated;
    }

    public function setValidated(bool $validated): static
    {
        $this->validated = $validated;

        return $this;
    }

    public function getDanseurDocuments(): ?DanseurDocuments
    {
        return $this->danseurDocuments;
    }

    public function setDanseurDocuments(?DanseurDocuments $danseurDocuments): static
    {
        // unset the owning side of the relation if necessary
        if ($danseurDocuments === null && $this->danseurDocuments !== null) {
            $this->danseurDocuments->setDanseur(null);
        }

        // set the owning side of the relation if necessary
        if ($danseurDocuments !== null && $danseurDocuments->getDanseur() !== $this) {
            $danseurDocuments->setDanseur($this);
        }

        $this->danseurDocuments = $danseurDocuments;

        return $this;
    }

}
