<?php

namespace App\Entity;

use AllowDynamicProperties;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\PropertyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: PropertyRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['property:read']],
    denormalizationContext: ['groups' => ['property:write']]
)]

#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]
class Property
{
    // Existing properties
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

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $bed = null;

    #[ORM\Column(length: 50)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $bath = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?int $sqft = null;

    #[ORM\Column(Types::STRING, nullable: true)]
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

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $saleOrRent = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?string $promotion = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2, nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?float $prixPromo = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['property:read', 'property:write'])]
    private ?array $img = [];

    private ?array $imageFiles = [];

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $neighborhood = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nbrEtage = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type_usage = null;

    #[ORM\Column(nullable: true)]
    private ?bool $meubler = null;

    #[ORM\Column(nullable: true)]
    private ?int $surfaceConstruite = null;

    #[ORM\Column(nullable: true)]
    private ?int $surfaceHabitable = null;

    #[ORM\Column(nullable: true)]
    private ?bool $chauffage = null;

    #[ORM\Column(nullable: true)]
    private ?bool $climatisation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $piscine = null;

    #[ORM\Column(nullable: true)]
    private ?int $surfaceTerrain = null;

    #[ORM\Column(nullable: true)]
    private ?bool $immeubleBureau = null;

    #[ORM\Column(nullable: true)]
    private ?bool $openSpace = null;

    #[ORM\Column(nullable: true)]
    private ?bool $entrepot = null;

    #[ORM\Column(nullable: true)]
    private ?bool $balcon = null;

    #[ORM\Column(nullable: true)]
    private ?bool $parking = null;

    #[ORM\Column(nullable: true)]
    private ?bool $cuisine = null;

    #[ORM\Column(nullable: true)]
    private ?bool $jardin = null;

    #[ORM\Column(nullable: true)]
    private ?int $charges = null;

    #[ORM\Column(nullable: true)]
    private ?int $ageBien = null;

    // New properties for Appartement
    #[ORM\Column(nullable: true)]
    private ?bool $concierge = null;

    #[ORM\Column(nullable: true)]
    private ?bool $residenceSurveillee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $orientation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $chauffageIndividuel = null;

    #[ORM\Column(nullable: true)]
    private ?int $hauteur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ascenseur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $facadePrincipale = null;

    #[ORM\Column(nullable: true)]
    private ?int $dpeKwh = null;

    // New properties for Maison/Villa
    #[ORM\Column(nullable: true)]
    private ?int $nombreNiveaux = null;

    #[ORM\Column(nullable: true)]
    private ?bool $terrasse = null;

    #[ORM\Column(nullable: true)]
    private ?bool $parkingAbrite = null;

    #[ORM\Column(nullable: true)]
    private ?bool $parkingExterieur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $energiePhotovoltaique = null;

    #[ORM\Column(nullable: true)]
    private ?bool $logeDomestique = null;

    #[ORM\Column(nullable: true)]
    private ?bool $debarras = null;

    #[ORM\Column(nullable: true)]
    private ?int $dpeCo2 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateConstruction = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRenovation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $camera = null;

    #[ORM\Column(nullable: true)]
    private ?bool $alarme = null;

    #[ORM\Column(nullable: true)]
    private ?bool $residenceFermee = null;

    // New properties for Bureau
    #[ORM\Column(nullable: true)]
    private ?bool $cloisonDur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $wc = null;

    #[ORM\Column(nullable: true)]
    private ?bool $immeubleMixte = null;

    // New properties for Magasin
    #[ORM\Column(nullable: true)]
    private ?bool $neuf = null;

    #[ORM\Column(nullable: true)]
    private ?bool $aRenover = null;

    #[ORM\Column(nullable: true)]
    private ?bool $doubleVitrine = null;

    #[ORM\Column(nullable: true)]
    private ?bool $extractionFumee = null;

    #[ORM\Column(nullable: true)]
    private ?bool $detecteurIncendie = null;

    #[ORM\Column(nullable: true)]
    private ?bool $systemeSecurite = null;

    // Existing getters and setters...

    // New getters and setters for Appartement properties
    public function isConcierge(): ?bool
    {
        return $this->concierge;
    }

    public function setConcierge(?bool $concierge): static
    {
        $this->concierge = $concierge;
        return $this;
    }

    public function isResidenceSurveillee(): ?bool
    {
        return $this->residenceSurveillee;
    }

    public function setResidenceSurveillee(?bool $residenceSurveillee): static
    {
        $this->residenceSurveillee = $residenceSurveillee;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getOrientation(): ?string
    {
        return $this->orientation;
    }

    public function setOrientation(?string $orientation): static
    {
        $this->orientation = $orientation;
        return $this;
    }

    public function isChauffageIndividuel(): ?bool
    {
        return $this->chauffageIndividuel;
    }

    public function setChauffageIndividuel(?bool $chauffageIndividuel): static
    {
        $this->chauffageIndividuel = $chauffageIndividuel;
        return $this;
    }

    public function getHauteur(): ?int
    {
        return $this->hauteur;
    }

    public function setHauteur(?int $hauteur): static
    {
        $this->hauteur = $hauteur;
        return $this;
    }

    public function isAscenseur(): ?bool
    {
        return $this->ascenseur;
    }

    public function setAscenseur(?bool $ascenseur): static
    {
        $this->ascenseur = $ascenseur;
        return $this;
    }

    public function getFacadePrincipale(): ?string
    {
        return $this->facadePrincipale;
    }

    public function setFacadePrincipale(?string $facadePrincipale): static
    {
        $this->facadePrincipale = $facadePrincipale;
        return $this;
    }

    public function getDpeKwh(): ?int
    {
        return $this->dpeKwh;
    }

    public function setDpeKwh(?int $dpeKwh): static
    {
        $this->dpeKwh = $dpeKwh;
        return $this;
    }

    // New getters and setters for Maison/Villa properties
    public function getNombreNiveaux(): ?int
    {
        return $this->nombreNiveaux;
    }

    public function setNombreNiveaux(?int $nombreNiveaux): static
    {
        $this->nombreNiveaux = $nombreNiveaux;
        return $this;
    }

    public function isTerrasse(): ?bool
    {
        return $this->terrasse;
    }

    public function setTerrasse(?bool $terrasse): static
    {
        $this->terrasse = $terrasse;
        return $this;
    }

    public function isParkingAbrite(): ?bool
    {
        return $this->parkingAbrite;
    }

    public function setParkingAbrite(?bool $parkingAbrite): static
    {
        $this->parkingAbrite = $parkingAbrite;
        return $this;
    }

    public function isParkingExterieur(): ?bool
    {
        return $this->parkingExterieur;
    }

    public function setParkingExterieur(?bool $parkingExterieur): static
    {
        $this->parkingExterieur = $parkingExterieur;
        return $this;
    }

    public function isEnergiePhotovoltaique(): ?bool
    {
        return $this->energiePhotovoltaique;
    }

    public function setEnergiePhotovoltaique(?bool $energiePhotovoltaique): static
    {
        $this->energiePhotovoltaique = $energiePhotovoltaique;
        return $this;
    }

    public function isLogeDomestique(): ?bool
    {
        return $this->logeDomestique;
    }

    public function setLogeDomestique(?bool $logeDomestique): static
    {
        $this->logeDomestique = $logeDomestique;
        return $this;
    }

    public function isDebarras(): ?bool
    {
        return $this->debarras;
    }

    public function setDebarras(?bool $debarras): static
    {
        $this->debarras = $debarras;
        return $this;
    }

    public function getDpeCo2(): ?int
    {
        return $this->dpeCo2;
    }

    public function setDpeCo2(?int $dpeCo2): static
    {
        $this->dpeCo2 = $dpeCo2;
        return $this;
    }

    public function getDateConstruction(): ?\DateTimeInterface
    {
        return $this->dateConstruction;
    }

    public function setDateConstruction(?\DateTimeInterface $dateConstruction): static
    {
        $this->dateConstruction = $dateConstruction;
        return $this;
    }

    public function getDateRenovation(): ?\DateTimeInterface
    {
        return $this->dateRenovation;
    }

    public function setDateRenovation(?\DateTimeInterface $dateRenovation): static
    {
        $this->dateRenovation = $dateRenovation;
        return $this;
    }

    public function isCamera(): ?bool
    {
        return $this->camera;
    }

    public function setCamera(?bool $camera): static
    {
        $this->camera = $camera;
        return $this;
    }

    public function isAlarme(): ?bool
    {
        return $this->alarme;
    }

    public function setAlarme(?bool $alarme): static
    {
        $this->alarme = $alarme;
        return $this;
    }

    public function isResidenceFermee(): ?bool
    {
        return $this->residenceFermee;
    }

    public function setResidenceFermee(?bool $residenceFermee): static
    {
        $this->residenceFermee = $residenceFermee;
        return $this;
    }

    // New getters and setters for Bureau properties
    public function isCloisonDur(): ?bool
    {
        return $this->cloisonDur;
    }

    public function setCloisonDur(?bool $cloisonDur): static
    {
        $this->cloisonDur = $cloisonDur;
        return $this;
    }

    public function isWc(): ?bool
    {
        return $this->wc;
    }

    public function setWc(?bool $wc): static
    {
        $this->wc = $wc;
        return $this;
    }

    public function isImmeubleMixte(): ?bool
    {
        return $this->immeubleMixte;
    }

    public function setImmeubleMixte(?bool $immeubleMixte): static
    {
        $this->immeubleMixte = $immeubleMixte;
        return $this;
    }

    // New getters and setters for Magasin properties
    public function isNeuf(): ?bool
    {
        return $this->neuf;
    }

    public function setNeuf(?bool $neuf): static
    {
        $this->neuf = $neuf;
        return $this;
    }

    public function isARenover(): ?bool
    {
        return $this->aRenover;
    }

    public function setARenover(?bool $aRenover): static
    {
        $this->aRenover = $aRenover;
        return $this;
    }

    public function isDoubleVitrine(): ?bool
    {
        return $this->doubleVitrine;
    }

    public function setDoubleVitrine(?bool $doubleVitrine): static
    {
        $this->doubleVitrine = $doubleVitrine;
        return $this;
    }

    public function isExtractionFumee(): ?bool
    {
        return $this->extractionFumee;
    }

    public function setExtractionFumee(?bool $extractionFumee): static
    {
        $this->extractionFumee = $extractionFumee;
        return $this;
    }

    public function isDetecteurIncendie(): ?bool
    {
        return $this->detecteurIncendie;
    }

    public function setDetecteurIncendie(?bool $detecteurIncendie): static
    {
        $this->detecteurIncendie = $detecteurIncendie;
        return $this;
    }

    public function isSystemeSecurite(): ?bool
    {
        return $this->systemeSecurite;
    }

    public function setSystemeSecurite(?bool $systemeSecurite): static
    {
        $this->systemeSecurite = $systemeSecurite;
        return $this;
    }

    // Existing getters and setters methods...
    // ... (all the other getters and setters from the original entity class)

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setCreatedAtValue(): void
    {
        if (!$this->createdAt) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

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

    public function getFees(): ?bool
    {
        return $this->fees;
    }

    public function setFees(?bool $fees): static
    {
        $this->fees = $fees;
        return $this;
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

    public function getSaleOrRent(): ?string
    {
        return $this->saleOrRent;
    }

    public function setSaleOrRent(string $saleOrRent): static
    {
        $this->saleOrRent = $saleOrRent;
        return $this;
    }

    public function getPromotion(): ?string
    {
        return $this->promotion;
    }

    public function setPromotion(?string $promotion): void
    {
        $this->promotion = $promotion;
    }

    public function getPrixPromo(): ?float
    {
        return $this->prixPromo;
    }

    public function setPrixPromo(?float $prixPromo): void
    {
        $this->prixPromo = $prixPromo;
    }

    public function getImg(): ?array
    {
        return $this->img;
    }

    public function getImgurl(): ?array
    {
        $imgUrlArray = array();
        foreach ($this->getImg() as $img) {
            $imgUrlArray[] = 'http://127.0.0.1:8000/uploads/images/' . $img[0];
        }
        return $imgUrlArray;
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(?string $neighborhood): self
    {
        $this->neighborhood = $neighborhood;
        return $this;
    }

    public function getNbrEtage(): ?string
    {
        return $this->nbrEtage;
    }

    public function setNbrEtage(?string $nbrEtage): static
    {
        $this->nbrEtage = $nbrEtage;
        return $this;
    }

    public function getTypeUsage(): ?string
    {
        return $this->type_usage;
    }

    public function setTypeUsage(?string $type_usage): static
    {
        $this->type_usage = $type_usage;
        return $this;
    }

    public function isMeubler(): ?bool
    {
        return $this->meubler;
    }

    public function setMeubler(?bool $meubler): static
    {
        $this->meubler = $meubler;
        return $this;
    }

    public function getSurfaceConstruite(): ?int
    {
        return $this->surfaceConstruite;
    }

    public function setSurfaceConstruite(?int $surfaceConstruite): static
    {
        $this->surfaceConstruite = $surfaceConstruite;
        return $this;
    }
    // Missing getters and setters

    public function getSurfaceHabitable(): ?int
    {
        return $this->surfaceHabitable;
    }

    public function setSurfaceHabitable(?int $surfaceHabitable): static
    {
        $this->surfaceHabitable = $surfaceHabitable;
        return $this;
    }

    public function isChauffage(): ?bool
    {
        return $this->chauffage;
    }

    public function setChauffage(?bool $chauffage): static
    {
        $this->chauffage = $chauffage;
        return $this;
    }

    public function isClimatisation(): ?bool
    {
        return $this->climatisation;
    }

    public function setClimatisation(?bool $climatisation): static
    {
        $this->climatisation = $climatisation;
        return $this;
    }

    public function isPiscine(): ?bool
    {
        return $this->piscine;
    }

    public function setPiscine(?bool $piscine): static
    {
        $this->piscine = $piscine;
        return $this;
    }

    public function getSurfaceTerrain(): ?int
    {
        return $this->surfaceTerrain;
    }

    public function setSurfaceTerrain(?int $surfaceTerrain): static
    {
        $this->surfaceTerrain = $surfaceTerrain;
        return $this;
    }

    public function isImmeubleBureau(): ?bool
    {
        return $this->immeubleBureau;
    }

    public function setImmeubleBureau(?bool $immeubleBureau): static
    {
        $this->immeubleBureau = $immeubleBureau;
        return $this;
    }

    public function isOpenSpace(): ?bool
    {
        return $this->openSpace;
    }

    public function setOpenSpace(?bool $openSpace): static
    {
        $this->openSpace = $openSpace;
        return $this;
    }

    public function isEntrepot(): ?bool
    {
        return $this->entrepot;
    }

    public function setEntrepot(?bool $entrepot): static
    {
        $this->entrepot = $entrepot;
        return $this;
    }

    public function isBalcon(): ?bool
    {
        return $this->balcon;
    }

    public function setBalcon(?bool $balcon): static
    {
        $this->balcon = $balcon;
        return $this;
    }

    public function isParking(): ?bool
    {
        return $this->parking;
    }

    public function setParking(?bool $parking): static
    {
        $this->parking = $parking;
        return $this;
    }

    public function isCuisine(): ?bool
    {
        return $this->cuisine;
    }

    public function setCuisine(?bool $cuisine): static
    {
        $this->cuisine = $cuisine;
        return $this;
    }

    public function isJardin(): ?bool
    {
        return $this->jardin;
    }

    public function setJardin(?bool $jardin): static
    {
        $this->jardin = $jardin;
        return $this;
    }

    public function getCharges(): ?int
    {
        return $this->charges;
    }

    public function setCharges(?int $charges): static
    {
        $this->charges = $charges;
        return $this;
    }

    public function getAgeBien(): ?int
    {
        return $this->ageBien;
    }

    public function setAgeBien(?int $ageBien): static
    {
        $this->ageBien = $ageBien;
        return $this;
    }

}