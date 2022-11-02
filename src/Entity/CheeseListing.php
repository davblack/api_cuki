<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Repository\CheeseListingRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;


#[ORM\Entity(repositoryClass: CheeseListingRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get',
        'post'
    ],
    itemOperations: [
        'get',
        'put'
    ],
    shortName: 'cheeses',
    attributes: [
      'pagination_items_per_page' => 10,
      'formats' => [
          'json',
          'jsonld',
          'html',
          'jsonhal',
          'csv' => 'text/csv'
      ]
    ],
    denormalizationContext: [
        'groups' => [self::CHEESE_LISTING_WRITE]
    ],
    normalizationContext: [
        'groups' => [self::CHEESE_LISTING_READ]
    ],
)]
#[ApiFilter(
    BooleanFilter::class,
    properties: [
        'isPublished',
    ],
)]
#[ApiFilter(
    SearchFilter::class,
    properties: [
        'title' => 'partial',
        'description' => 'partial'
    ]
)]
#[ApiFilter(
    RangeFilter::class,
    properties: [
        'price'
    ]
)]
#[ApiFilter(
    PropertyFilter::class,
)]
class CheeseListing
{
    public const CHEESE_LISTING_READ = "cheese_listing:read";
    public const CHEESE_LISTING_WRITE = "cheese_listing:write";


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups([self::CHEESE_LISTING_READ, self::CHEESE_LISTING_WRITE])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups([self::CHEESE_LISTING_READ])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups([self::CHEESE_LISTING_READ, self::CHEESE_LISTING_WRITE])]
    private ?int $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $cretedAt = null;

    #[ORM\Column]
    private ?bool $isPublished = false;

    #[ORM\ManyToOne(inversedBy: 'cheeseListings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $onwer = null;

    public function __construct(string $title)
    {
        $this->cretedAt = new \DateTimeImmutable();
        $this->title = $title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
/*
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
*/
    public function getDescription(): ?string
    {
        return $this->description;
    }

    #[Groups([self::CHEESE_LISTING_READ])]
    public function getShortDescription(): ?string
    {
        if(strlen($this->description) < 40){
            return $this->description;
        }
        return substr($this->description, 0, 40).'...';
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    #[Groups([self::CHEESE_LISTING_WRITE])]
    #[SerializedName('description')]
    public function setTextDescription(string $description): self
    {
        $this->description = nl2br($description);

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCretedAt(): ?\DateTimeInterface
    {
        return $this->cretedAt;
    }

    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCretedAt())->diffForHumans();
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getOnwer(): ?User
    {
        return $this->onwer;
    }

    public function setOnwer(?User $onwer): self
    {
        $this->onwer = $onwer;

        return $this;
    }

}
