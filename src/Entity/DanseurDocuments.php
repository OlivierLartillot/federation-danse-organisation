<?php

namespace App\Entity;

use App\Repository\DanseurDocumentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: DanseurDocumentsRepository::class)]
#[Vich\Uploadable]
class DanseurDocuments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /************************ papiers identité *************************/
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $identity = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'identity', fileNameProperty: 'identity')]
    private ?File $identityFile = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $identityUpdatedAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $maxValidityIdentityDate = null;

    /****************************************************************************/
    /************************ Certif medical *************************/

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $medicalCertificate = null;
    
    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'medicalCertificate', fileNameProperty: 'medicalCertificate')]
    private ?File $medicalCertificateFile = null;
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $medicalCertificateUpdatedAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $maxValidityCertificateDate = null;

    /****************************************************************************/
    /************************ permission photographique *************************/

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photographicPermission = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'photographicPermission', fileNameProperty: 'photographicPermission')]
    private ?File $photographicPermissionFile = null;
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $photographicPermissionUpdatedAt = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $maxValidityPhotographicPermissionDate = null;


    /****************************************************************************/
    /****************************************************************************/

    #[ORM\OneToOne(inversedBy: 'danseurDocuments', cascade: ['persist', 'remove'])]
    private ?Danseur $danseur = null;

    #[ORM\ManyToOne(inversedBy: 'danseurDocuments')]
    private ?Season $validatedForSeason = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentity(): ?string
    {
        return $this->identity;
    }

    public function setIdentity(?string $identity): static
    {
        $this->identity = $identity;

        return $this;
    }

    public function getMaxValidityIdentityDate(): ?\DateTimeImmutable
    {
        return $this->maxValidityIdentityDate;
    }

    public function setMaxValidityIdentityDate(?\DateTimeImmutable $maxValidityIdentityDate): static
    {
        $this->maxValidityIdentityDate = $maxValidityIdentityDate;

        return $this;
    }

    public function getMedicalCertificate(): ?string
    {
        return $this->medicalCertificate;
    }

    public function setMedicalCertificate(?string $medicalCertificate): static
    {
        $this->medicalCertificate = $medicalCertificate;

        return $this;
    }

    public function getMaxValidityCertificateDate(): ?\DateTimeImmutable
    {
        return $this->maxValidityCertificateDate;
    }

    public function setMaxValidityCertificateDate(?\DateTimeImmutable $maxValidityCertificateDate): static
    {
        $this->maxValidityCertificateDate = $maxValidityCertificateDate;

        return $this;
    }

    public function getPhotographicPermission(): ?string
    {
        return $this->photographicPermission;
    }

    public function setPhotographicPermission(?string $photographicPermission): static
    {
        $this->photographicPermission = $photographicPermission;

        return $this;
    }

    public function getMaxValidityPhotographicPermissionDate(): ?\DateTimeImmutable
    {
        return $this->maxValidityPhotographicPermissionDate;
    }

    public function setMaxValidityPhotographicPermissionDate(?\DateTimeImmutable $maxValidityPhotographicPermissionDate): static
    {
        $this->maxValidityPhotographicPermissionDate = $maxValidityPhotographicPermissionDate;

        return $this;
    }

    public function getDanseur(): ?Danseur
    {
        return $this->danseur;
    }

    public function setDanseur(?Danseur $danseur): static
    {
        $this->danseur = $danseur;

        return $this;
    }

     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $identityFile
     */
    public function setIdentityFile(?File $identityFile = null): void
    {
        $this->identityFile = $identityFile;

        if (null !== $identityFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->identityUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getIdentityFile(): ?File
    {
        return $this->identityFile;
    }




     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $medicalCertificateFile
     */
    public function setMedicalCertificateFile(?File $medicalCertificateFile = null): void
    {
        $this->medicalCertificateFile = $medicalCertificateFile;

        if (null !== $medicalCertificateFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->medicalCertificateUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getMedicalCertificateFile(): ?File
    {
        return $this->medicalCertificateFile;
    }



     /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $photographicPermissionFile
     */
    public function setPhotographicPermissionFile(?File $photographicPermissionFile = null): void
    {
        $this->photographicPermissionFile = $photographicPermissionFile;

        if (null !== $photographicPermissionFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->photographicPermissionUpdatedAt = new \DateTimeImmutable();
        }
    }

    public function getPhotographicPermissionFile(): ?File
    {
        return $this->photographicPermissionFile;
    }

    public function getValidatedForSeason(): ?Season
    {
        return $this->validatedForSeason;
    }

    public function setValidatedForSeason(?Season $validatedForSeason): static
    {
        $this->validatedForSeason = $validatedForSeason;

        return $this;
    }


}
