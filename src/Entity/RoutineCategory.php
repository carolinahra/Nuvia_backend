<?php

namespace App\Entity;

use App\Repository\RoutineCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoutineCategoryRepository::class)]
#[ORM\Table(name: 'routine_category')]
class RoutineCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $description = null;

    /** @var Collection<int, Routine> */
    #[ORM\ManyToMany(targetEntity: Routine::class, mappedBy: 'categories')]
    private Collection $routines;

    public function __construct()
    {
        $this->routines = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    /** @return Collection<int, Routine> */
    public function getRoutines(): Collection { return $this->routines; }
}
