<?php

namespace App\Entity;

use App\Repository\RoutineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoutineRepository::class)]
#[ORM\Table(name: 'routine')]
class Routine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $durationMinutes = null;

    /** @var Collection<int, RoutineHasExercise> */
    #[ORM\OneToMany(mappedBy: 'routine', targetEntity: RoutineHasExercise::class, cascade: ['persist', 'remove'])]
    private Collection $routineHasExercises;

    /** @var Collection<int, RoutineCategory> */
    #[ORM\ManyToMany(targetEntity: RoutineCategory::class, inversedBy: 'routines')]
    #[ORM\JoinTable(
        name: 'routine_has_category',
        joinColumns: [new ORM\JoinColumn(name: 'routine_id', referencedColumnName: 'id', onDelete: 'CASCADE')],
        inverseJoinColumns: [new ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    )]
    private Collection $categories;

    /** @var Collection<int, TrainingSession> */
    #[ORM\OneToMany(mappedBy: 'routine', targetEntity: TrainingSession::class)]
    private Collection $trainingSessions;

    /** @var Collection<int, User> */
    #[ORM\OneToMany(mappedBy: 'defaultRoutine', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->routineHasExercises = new ArrayCollection();
        $this->categories          = new ArrayCollection();
        $this->trainingSessions    = new ArrayCollection();
        $this->users               = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getDurationMinutes(): ?int { return $this->durationMinutes; }
    public function setDurationMinutes(?int $durationMinutes): static { $this->durationMinutes = $durationMinutes; return $this; }

    /** @return Collection<int, RoutineHasExercise> */
    public function getRoutineHasExercises(): Collection { return $this->routineHasExercises; }

    public function addRoutineHasExercise(RoutineHasExercise $rhe): static
    {
        if (!$this->routineHasExercises->contains($rhe)) {
            $this->routineHasExercises->add($rhe);
            $rhe->setRoutine($this);
        }
        return $this;
    }

    public function removeRoutineHasExercise(RoutineHasExercise $rhe): static
    {
        $this->routineHasExercises->removeElement($rhe);
        return $this;
    }

    /** @return Collection<int, RoutineCategory> */
    public function getCategories(): Collection { return $this->categories; }

    public function addCategory(RoutineCategory $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }
        return $this;
    }

    public function removeCategory(RoutineCategory $category): static
    {
        $this->categories->removeElement($category);
        return $this;
    }

    /** @return Collection<int, TrainingSession> */
    public function getTrainingSessions(): Collection { return $this->trainingSessions; }

    /** @return Collection<int, User> */
    public function getUsers(): Collection { return $this->users; }
}
