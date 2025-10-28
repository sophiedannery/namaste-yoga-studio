<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatarUrl = null;

    #[ORM\Column(options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $specialties = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'student')]
    private Collection $reviews;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'teacher')]
    private Collection $sessions;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'cancelledBy')]
    private Collection $sessionsCanceled;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'student')]
    private Collection $reservations;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'cancelledBy')]
    private Collection $reservations_cancelled;

    /**
     * @var Collection<int, Suspension>
     */
    #[ORM\OneToMany(targetEntity: Suspension::class, mappedBy: 'student')]
    private Collection $suspensions_student;

    /**
     * @var Collection<int, Suspension>
     */
    #[ORM\OneToMany(targetEntity: Suspension::class, mappedBy: 'admin_res')]
    private Collection $suspensions_admin_res;

    public function __construct()
    {
        $now = new \DateTimeImmutable('now');
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->reviews = new ArrayCollection();
        $this->sessions = new ArrayCollection();
        $this->sessionsCanceled = new ArrayCollection();
        $this->reservations = new ArrayCollection();
        $this->reservations_cancelled = new ArrayCollection();
        $this->suspensions_student = new ArrayCollection();
        $this->suspensions_admin_res = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }


    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): static
    {
        $this->avatarUrl = $avatarUrl;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    public function getSpecialties(): ?string
    {
        return $this->specialties;
    }

    public function setSpecialties(?string $specialties): static
    {
        $this->specialties = $specialties;

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

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setStudent($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        $this->reviews->removeElement($review);
    // ne pas setStudent(null) car FK non-nullable
        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setTeacher($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        $this->sessions->removeElement($session);
        // ne pas setTeacher(null) (FK non-nullable)
        return $this;
    }


    /**
     * @return Collection<int, Session>
     */
    public function getSessionsCanceled(): Collection
    {
        return $this->sessionsCanceled;
    }

    public function addSessionsCanceled(Session $sessionsCanceled): static
    {
        if (!$this->sessionsCanceled->contains($sessionsCanceled)) {
            $this->sessionsCanceled->add($sessionsCanceled);
            $sessionsCanceled->setCancelledBy($this);
        }

        return $this;
    }

    public function removeSessionsCanceled(Session $sessionsCanceled): static
    {
        if ($this->sessionsCanceled->removeElement($sessionsCanceled)) {
            // set the owning side to null (unless already changed)
            if ($sessionsCanceled->getCancelledBy() === $this) {
                $sessionsCanceled->setCancelledBy(null);
            }
        }

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
            $reservation->setStudent($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getStudent() === $this) {
                $reservation->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservationsCancelled(): Collection
    {
        return $this->reservations_cancelled;
    }

    public function addReservationsCancelled(Reservation $reservationsCancelled): static
    {
        if (!$this->reservations_cancelled->contains($reservationsCancelled)) {
            $this->reservations_cancelled->add($reservationsCancelled);
            $reservationsCancelled->setCancelledBy($this);
        }

        return $this;
    }

    public function removeReservationsCancelled(Reservation $reservationsCancelled): static
    {
        if ($this->reservations_cancelled->removeElement($reservationsCancelled)) {
            // set the owning side to null (unless already changed)
            if ($reservationsCancelled->getCancelledBy() === $this) {
                $reservationsCancelled->setCancelledBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Suspension>
     */
    public function getSuspensionsStudent(): Collection
    {
        return $this->suspensions_student;
    }

    public function addSuspensionsStudent(Suspension $suspensionsStudent): static
    {
        if (!$this->suspensions_student->contains($suspensionsStudent)) {
            $this->suspensions_student->add($suspensionsStudent);
            $suspensionsStudent->setStudent($this);
        }

        return $this;
    }

    public function removeSuspensionsStudent(Suspension $suspensionsStudent): static
    {
        if ($this->suspensions_student->removeElement($suspensionsStudent)) {
            // set the owning side to null (unless already changed)
            if ($suspensionsStudent->getStudent() === $this) {
                $suspensionsStudent->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Suspension>
     */
    public function getSuspensionsAdminRes(): Collection
    {
        return $this->suspensions_admin_res;
    }

    public function addSuspensionsAdminRe(Suspension $suspensionsAdminRe): static
    {
        if (!$this->suspensions_admin_res->contains($suspensionsAdminRe)) {
            $this->suspensions_admin_res->add($suspensionsAdminRe);
            $suspensionsAdminRe->setAdminRes($this);
        }

        return $this;
    }

    public function removeSuspensionsAdminRe(Suspension $suspensionsAdminRe): static
    {
        if ($this->suspensions_admin_res->removeElement($suspensionsAdminRe)) {
            // set the owning side to null (unless already changed)
            if ($suspensionsAdminRe->getAdminRes() === $this) {
                $suspensionsAdminRe->setAdminRes(null);
            }
        }

        return $this;
    }
}
