<?php

namespace App\Entity;

use App\Repository\RoutineHasExerciseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Tabla intermedia Routine ↔ Exercise con campos adicionales.
 * Al tener campos extra no puede ser una simple @ManyToMany, por eso
 * se modela como entidad propia con dos @ManyToOne.
 */
#[ORM\Entity(repositoryClass: RoutineHasExerciseRepository::class)]
#[ORM\Table(name: 'routine_has_exercise')]
#[ORM\UniqueConstraint(name: 'uq_routine_exercise', columns: ['routine_id', 'exercise_id'])]
class RoutineHasExercise
{
    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Routine::class, inversedBy: 'routineHasExercises')]
    #[ORM\JoinColumn(name: 'routine_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Routine $routine;

    #[ORM\Id]
    #[ORM\ManyToOne(targetEntity: Exercise::class, inversedBy: 'routineHasExercises')]
    #[ORM\JoinColumn(name: 'exercise_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Exercise $exercise;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $sets = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $reps = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $restSeconds = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $orderIndex = null;

    public function getRoutine(): Routine { return $this->routine; }
    public function setRoutine(Routine $routine): static { $this->routine = $routine; return $this; }

    public function getExercise(): Exercise { return $this->exercise; }
    public function setExercise(Exercise $exercise): static { $this->exercise = $exercise; return $this; }

    public function getSets(): ?int { return $this->sets; }
    public function setSets(?int $sets): static { $this->sets = $sets; return $this; }

    public function getReps(): ?int { return $this->reps; }
    public function setReps(?int $reps): static { $this->reps = $reps; return $this; }

    public function getRestSeconds(): ?int { return $this->restSeconds; }
    public function setRestSeconds(?int $restSeconds): static { $this->restSeconds = $restSeconds; return $this; }

    public function getOrderIndex(): ?int { return $this->orderIndex; }
    public function setOrderIndex(?int $orderIndex): static { $this->orderIndex = $orderIndex; return $this; }
}
