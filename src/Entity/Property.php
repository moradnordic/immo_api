<?php

namespace App\Entity;

use AllowDynamicProperties;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PropertyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['property:read']],
    denormalizationContext: ['groups' => ['property:write']]
)]
#[Vich\Uploadable]

class Property
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['property:read'])]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?int $price = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?int $surface = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?int $rooms = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?int $beds = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'property')]
    #[Groups(['property:read', 'property:write'])]
    private ?Agence $agence = null;

    // New fields to match latestForRent interface
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $thumbnail = null;

    #[ORM\Column(length: 255)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $propertyStatus = null;

    #[ORM\Column(length: 255)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $home = null;

    #[ORM\Column(length: 50,nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $bed = null;

    #[ORM\Column(length: 50)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $bath = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?int $sqft = null;

    #[ORM\Column( Types::STRING, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private string|null $propertyType = null;



    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?array $labels = [];

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?bool $sale = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?bool $fees = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?bool $openHouse = null;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?bool $sold = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $params = [];

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?int $propertyTab = null;




    // New field for sale or rent
    #[ORM\Column(length: 50,nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $saleOrRent = null;


    // Getters and setters for the new fields
    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(?string $title): static
    {
        $this->title = $title;
        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }
    public function getPrice(): ?int
    {
        return $this->price;
    }
    public function setPrice(?int $price): static
    {
        $this->price = $price;
        return $this;
    }
    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(?int $surface): static
    {
        $this->surface = $surface;
        return $this;
    }
    public function getRooms(): ?int
    {
        return $this->rooms;
    }
    public function setRooms(?int $rooms): static
    {
        $this->rooms = $rooms;
        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }
    public function setAgence(?Agence $agence): static
    {
        $this->agence = $agence;
        return $this;
    }
    public function getCreatedAt()
    {
    return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }
    public function getBeds(): ?int
    {
        return $this->beds;
    }
    public function setBeds(?int $beds): static
    {
        $this->beds = $beds;
        return $this;
    }
    public function getBath(): ?string
    {
        return $this->bath;
    }
    public function setBath(?string $bath): static
    {
        $this->bath = $bath;
        return $this;
    }
    public function getSqft(): ?int
    {
        return $this->sqft;
    }
    public function setSqft(?int $sqft): static
    {
        $this->sqft = $sqft;
        return $this;
    }
    public function getPropertyType(): ?string
    {
        return $this->propertyType;
    }
    public function setPropertyType(?string $propertyType): static
    {
        $this->propertyType = $propertyType;
        return $this;
    }

    public function getLabels(): array
    {
        return $this->labels;
    }
    public function setLabels(array $labels): static
    {
        $this->labels = $labels;
        return $this;
    }
    public function getSale(): ?bool
    {
        return $this->sale;
    }
    public function setSale(?bool $sale): static
    {
        $this->sale = $sale;
        return $this;
    }
    public function getFees(): ?int
    {
        return $this->fees;
    }
    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getPropertyStatus(): ?string
    {
        return $this->propertyStatus;
    }

    public function setPropertyStatus(string $propertyStatus): static
    {
        $this->propertyStatus = $propertyStatus;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getHome(): ?string
    {
        return $this->home;
    }

    public function setHome(?string $home): static
    {
        $this->home = $home;

        return $this;
    }

    public function getBed(): ?string
    {
        return $this->bed;
    }

    public function setBed(string $bed): static
    {
        $this->bed = $bed;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }
    
    public function setFees(?bool $fees): static
    {
        $this->fees = $fees;

        return $this;
    }

    public function getOpenHouse(): ?bool
    {
        return $this->openHouse;
    }

    public function setOpenHouse(?bool $openHouse): static
    {
        $this->openHouse = $openHouse;

        return $this;
    }

    public function getSold(): ?bool
    {
        return $this->sold;
    }

    public function setSold(?bool $sold): static
    {
        $this->sold = $sold;

        return $this;
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    public function setParams(?array $params): static
    {
        $this->params = $params;

        return $this;
    }

    public function getPropertyTab(): ?int
    {
        return $this->propertyTab;
    }

    public function setPropertyTab(?int $propertyTab): static
    {
        $this->propertyTab = $propertyTab;

        return $this;
    }

    // Getter and Setter for saleOrRent
    public function getSaleOrRent(): ?string
    {
        return $this->saleOrRent;
    }

    public function setSaleOrRent(string $saleOrRent): static
    {
        $this->saleOrRent = $saleOrRent;

        return $this;
    }

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $img = [];

    // Not stored in the database, only for file uploads
    private ?array $imageFiles = null;

    // Setters and Getters

    public function getImg(): ?array
    {
        return $this->img;
    }

    public function setImg(?array $img): self
    {
        $this->img = $img;
        return $this;
    }

    public function getImageFiles(): ?array
    {
        return $this->imageFiles;
    }

    public function setImageFiles(?array $imageFiles): self
    {
        $this->imageFiles = $imageFiles;
        return $this;
    }
    #[ORM\Column(length: 255)]
    private ?string $city = null;

    // Getter and Setter for city
    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }
    #[ORM\Column(length: 255)]
    private ?string $neighborhood = null;

    // Getter and Setter for neighborhood
    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(?string $neighborhood): self
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }



}
