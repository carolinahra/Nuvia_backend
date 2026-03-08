<?php

namespace App\Entity;

use App\Repository\TrainingSessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainingSessionRepository::class)]
#[ORM\Table(name: 'training_session')]
class TrainingSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'trainingSessions')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Routine::class, inversedBy: 'trainingSessions')]
    #[ORM\JoinColumn(name: 'routine_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Routine $routine;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $durationMinutes = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $caloriesEstimated = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function getId(): ?int { return $this->id; }

    public function getUser(): User { return $this->user; }
    public function setUser(User $user): static { $this->user = $user; return $this; }

    public function getRoutine(): Routine { return $this->routine; }
    public function setRoutine(Routine $routine): static { $this->routine = $routine; return $this; }

    public function getDurationMinutes(): ?int { return $this->durationMinutes; }
    public function setDurationMinutes(?int $durationMinutes): static { $this->durationMinutes = $durationMinutes; return $this; }

    public function getCaloriesEstimated(): ?int { return $this->caloriesEstimated; }
    public function setCaloriesEstimated(?int $cal): static { $this->caloriesEstimated = $cal; return $this; }

    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): static { $this->createdAt = $createdAt; return $this; }
}
