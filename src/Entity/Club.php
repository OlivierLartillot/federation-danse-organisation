<?php

namespace App\Entity;

use App\Repository\ClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $correspondents = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $adress = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $phonePrimary = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $phoneSecondary = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailPrimary = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailSecondary = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fax = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $webSite = null;

    #[ORM\OneToMany(targetEntity: Championship::class, mappedBy: 'organizingClub')]
    private Collection $championships;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $owner = null;

    #[ORM\OneToMany(targetEntity: Danseur::class, mappedBy: 'club')]
    private Collection $danseurs;

    #[ORM\OneToMany(targetEntity: Licence::class, mappedBy: 'club')]
    private Collection $licences;

    #[ORM\Column(nullable: false)]
    private ?bool $archived = null;


    public function __construct()
    {
        $this->championships = new ArrayCollection();
        $this->danseurs = new ArrayCollection();
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
        return ucwords(strtolower($this->name));
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCorrespondents(): ?string
    {
        return $this->correspondents;
    }

    public function setCorrespondents(?string $correspondents): static
    {
        $this->correspondents = $correspondents;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPhonePrimary(): ?string
    {
        return $this->phonePrimary;
    }

    public function setPhonePrimary(?string $phonePrimary): static
    {
        $this->phonePrimary = $phonePrimary;

        return $this;
    }

    public function getPhoneSecondary(): ?string
    {
        return $this->phoneSecondary;
    }

    public function setPhoneSecondary(?string $phoneSecondary): static
    {
        $this->phoneSecondary = $phoneSecondary;

        return $this;
    }

    public function getEmailPrimary(): ?string
    {
        return $this->emailPrimary;
    }

    public function setEmailPrimary(?string $emailPrimary): static
    {
        $this->emailPrimary = $emailPrimary;

        return $this;
    }

    public function getEmailSecondary(): ?string
    {
        return $this->emailSecondary;
    }

    public function setEmailSecondary(?string $emailSecondary): static
    {
        $this->emailSecondary = $emailSecondary;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): static
    {
        $this->fax = $fax;

        return $this;
    }

    public function getWebSite(): ?string
    {
        return $this->webSite;
    }

    public function setWebSite(?string $webSite): static
    {
        $this->webSite = $webSite;

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
            $championship->setOrganizingClub($this);
        }

        return $this;
    }

    public function removeChampionship(Championship $championship): static
    {
        if ($this->championships->removeElement($championship)) {
            // set the owning side to null (unless already changed)
            if ($championship->getOrganizingClub() === $this) {
                $championship->setOrganizingClub(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

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
            $danseur->setClub($this);
        }

        return $this;
    }

    public function removeDanseur(Danseur $danseur): static
    {
        if ($this->danseurs->removeElement($danseur)) {
            // set the owning side to null (unless already changed)
            if ($danseur->getClub() === $this) {
                $danseur->setClub(null);
            }
        }

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
            $licence->setClub($this);
        }

        return $this;
    }

    public function removeLicence(Licence $licence): static
    {
        if ($this->licences->removeElement($licence)) {
            // set the owning side to null (unless already changed)
            if ($licence->getClub() === $this) {
                $licence->setClub(null);
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
