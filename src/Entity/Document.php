<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $documentPath = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $apparitionOrder = null;

    #[ORM\Column(nullable: true)]
    private ?bool $published = null;

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

    public function getDocumentPath(): ?string
    {
        return $this->documentPath;
    }

    public function setDocumentPath(string $documentPath): static
    {
        $this->documentPath = $documentPath;

        return $this;
    }

    public function getApparitionOrder(): ?int
    {
        return $this->apparitionOrder;
    }

    public function setApparitionOrder(?int $apparitionOrder): static
    {
        $this->apparitionOrder = $apparitionOrder;

        return $this;
    }

    public function isPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): static
    {
        $this->published = $published;

        return $this;
    }
}
