<?php

namespace App\Entity;

use App\Repository\DishRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DishRepository::class)]
#[ORM\Table(name: 'dish')]
class Dish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $ingredients = null;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $caloriesPerServing = null;

    /** @var Collection<int, DietMeal> */
    #[ORM\OneToMany(mappedBy: 'dish', targetEntity: DietMeal::class, cascade: ['persist', 'remove'])]
    private Collection $dietMeals;

    /** @var Collection<int, UserMealLog> */
    #[ORM\OneToMany(mappedBy: 'dish', targetEntity: UserMealLog::class, cascade: ['persist', 'remove'])]
    private Collection $userMealLogs;

    public function __construct()
    {
        $this->dietMeals    = new ArrayCollection();
        $this->userMealLogs = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getIngredients(): ?string { return $this->ingredients; }
    public function setIngredients(?string $ingredients): static { $this->ingredients = $ingredients; return $this; }

    public function getCaloriesPerServing(): ?int { return $this->caloriesPerServing; }
    public function setCaloriesPerServing(?int $cal): static { $this->caloriesPerServing = $cal; return $this; }

    /** @return Collection<int, DietMeal> */
    public function getDietMeals(): Collection { return $this->dietMeals; }

    /** @return Collection<int, UserMealLog> */
    public function getUserMealLogs(): Collection { return $this->userMealLogs; }
}
