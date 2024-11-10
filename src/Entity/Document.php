<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;

#[ORM\Entity]
#[Uploadable]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $filePath = null;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\User')]
    private User $uploadedBy;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $uploadedAt;

    #[UploadableField(mapping: 'document_files', fileNameProperty: 'filePath')]
    private ?File $file = null;

    public function getId(): int
    {
        return $this->id;
    }
    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string
    {

        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): static
    {
        $this->filePath = $filePath;
        return $this;
    }

    public function getUploadedBy()
    {
        return $this->uploadedBy;
    }

    public function setUploadedBy($uploadedBy): void
    {
        $this->uploadedBy = $uploadedBy;
    }

    public function getUploadedAt(): \DateTimeInterface
    {
        return $this->uploadedAt;
    }

    public function setUploadedAt(\DateTimeInterface $uploadedAt): void
    {
        $this->uploadedAt = $uploadedAt;
    }

    public function setFile(?File $file = null): static
    {
        $this->file = $file;
        if ($file) {
            $this->uploadedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }
}
