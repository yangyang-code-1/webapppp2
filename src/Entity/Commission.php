<?php

namespace App\Entity;

use App\Repository\CommissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Commission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;
    #[ORM\ManyToOne(inversedBy: 'commissionsCreated')]
    #[ORM\JoinColumn(nullable: false)]

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'commissionsCreated')]
    #[ORM\JoinColumn(nullable: true)]  // Adjust nullable as per your choice
    private ?User $artist = null;

    public function getArtist(): ?User
    {
        return $this->artist;
    }

    public function setArtist(?User $artist): static
    {
        $this->artist = $artist;

        return $this;
    }

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commissionsRequested')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $client = null;

    #[ORM\Column(length: 100)]
    private ?string $category = null;

    // #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'commissions')]
    // private ?self $User = null;

    // /**
    //  * @var Collection<int, self>
    //  */
    // #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'User')]
    // private Collection $commissions;


    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->commissions = new ArrayCollection();
    }

    // Automatically update the 'updatedAt' field whenever the entity is changed
    #[ORM\PreUpdate]
    public function onPreUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }

    // ---- Getters & Setters ----

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;
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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    // public function getUser(): ?self
    // {
    //     return $this->User;
    // }

    // public function setUser(?self $User): static
    // {
    //     $this->User = $User;

    //     return $this;
    // }

    // /**
    //  * @return Collection<int, self>
    //  */
    // public function getCommissions(): Collection
    // {
    //     return $this->commissions;
    // }

    // public function addCommission(self $commission): static
    // {
    //     if (!$this->commissions->contains($commission)) {
    //         $this->commissions->add($commission);
    //         $commission->setUser($this);
    //     }

    //     return $this;
    // }

    // public function removeCommission(self $commission): static
    // {
    //     if ($this->commissions->removeElement($commission)) {
    //         // set the owning side to null (unless already changed)
    //         if ($commission->getUser() === $this) {
    //             $commission->setUser(null);
    //         }
    //     }

    //     return $this;
    // }

}
