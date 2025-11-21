<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getSessions", "getReservations"])]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La date de début est obligatoire.")]
    #[Assert\Type(\DateTimeImmutable::class, message: "Format de date invalide.")]
    #[Assert\GreaterThan("now", message: "La session doit commencer dans le futur.")]
    #[Groups(["getSessions", "getReservations"])]
    private ?\DateTimeImmutable $startAt = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La date de fin est obligatoire.")]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Assert\Expression(
        "this.getEndAt() > this.getStartAt()",
        message: "La fin doit être après le début."
    )]
    #[Groups(["getSessions", "getReservations"])]
    private ?\DateTimeImmutable $endAt = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "La capacité est obligatoire.")]
    #[Assert\Positive(message: "La capacité doit être supérieure à 0.")]
    #[Assert\Range(
        min: 1,
        max: 99,
        notInRangeMessage: "La capacité doit être comprise entre {{ min }} et {{ max }}."
    )]
    #[Groups(["getSessions", "getReservations"])]
    private ?int $capacity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2, nullable: true)]
    #[Assert\Regex(
        pattern: "/^\d+(\.\d{1,2})?$/",
        message: "Le prix doit être un nombre valide avec 0 à 2 décimales."
    )]
    #[Groups(["getSessions", "getReservations"])]
    private ?string $price = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(
        max: 2000,
        maxMessage: "La description ne peut pas dépasser {{ limit }} caractères."
    )]
    #[Groups(["getSessions", "getReservations"])]
    private ?string $details = null;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(
        choices: ['SCHEDULED', 'CANCELLED', 'COMPLETED'],
        message: "Le statut n'est pas valide."
    )]
    #[Groups(["getSessions", "getReservations"])]
    private string $status = 'SCHEDULED';

    #[ORM\Column(nullable: true)]
    #[Groups(["getSessions", "getReservations"])]
    private ?\DateTimeImmutable $cancelledAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["getSessions", "getReservations"])]
    private ?string $cancelReason = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;




    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Un professeur doit être sélectionné.")]
    #[Groups(["getSessions", "getReservations"])]
    private ?User $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'sessionsCanceled')]
    #[Groups(["getSessions", "getReservations"])]
    private ?User $cancelledBy = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Un type de cours doit être sélectionné.")]
    #[Groups(["getSessions", "getReservations"])]
    private ?ClassType $classType = null;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[Groups(["getSessions", "getReservations"])]
    private ?Room $room = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'session')]
    private Collection $reservations;



    public function __construct()
    {
        $now = new \DateTimeImmutable('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->status = 'SCHEDULED';
        $this->reservations = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeImmutable $startAt): static
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeImmutable $endAt): static
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCancelledAt(): ?\DateTimeImmutable
    {
        return $this->cancelledAt;
    }

    public function setCancelledAt(?\DateTimeImmutable $canceledAt): static
    {
        $this->cancelledAt = $canceledAt;

        return $this;
    }

    public function getCancelReason(): ?string
    {
        return $this->cancelReason;
    }

    public function setCancelReason(?string $cancelReason): static
    {
        $this->cancelReason = $cancelReason;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(User $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getCancelledBy(): ?User
    {
        return $this->cancelledBy;
    }

    public function setCancelledBy(?User $cancelledBy): static
    {
        $this->cancelledBy = $cancelledBy;

        return $this;
    }

    public function getClassType(): ?ClassType
    {
        return $this->classType;
    }

    public function setClassType(ClassType $classType): static
    {
        $this->classType = $classType;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): static
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setSession($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSession() === $this) {
                $reservation->setSession(null);
            }
        }

        return $this;
    }
}
