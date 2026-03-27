<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciseRepository::class)]
#[ORM\Table(name: 'exercise')]
class Exercise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    /** alta | media | baja */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $intensity = null;

    /** @var Collection<int, RoutineHasExercise> */
    #[ORM\OneToMany(mappedBy: 'exercise', targetEntity: RoutineHasExercise::class, cascade: ['persist', 'remove'])]
    private Collection $routineHasExercises;

    public function __construct()
    {
        $this->routineHasExercises = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getIntensity(): ?string { return $this->intensity; }
    public function setIntensity(?string $intensity): static { $this->intensity = $intensity; return $this; }

    /** @return Collection<int, RoutineHasExercise> */
    public function getRoutineHasExercises(): Collection { return $this->routineHasExercises; }
}
