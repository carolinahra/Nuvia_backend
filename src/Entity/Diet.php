<?php

namespace App\Entity;

use App\Repository\DietRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DietRepository::class)]
#[ORM\Table(name: 'diet')]
class Diet
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
    private ?int $totalDailyCalories = null;

    /** perder | mantener | ganar */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $goal = null;

    /** vegano, vegetariano, etc. */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $dietType = null;

    /** @var Collection<int, DietMeal> */
    #[ORM\OneToMany(mappedBy: 'diet', targetEntity: DietMeal::class, cascade: ['persist', 'remove'])]
    private Collection $dietMeals;

    /** @var Collection<int, User> */
    #[ORM\OneToMany(mappedBy: 'defaultDiet', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->dietMeals = new ArrayCollection();
        $this->users     = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getTotalDailyCalories(): ?int { return $this->totalDailyCalories; }
    public function setTotalDailyCalories(?int $totalDailyCalories): static { $this->totalDailyCalories = $totalDailyCalories; return $this; }

    public function getGoal(): ?string { return $this->goal; }
    public function setGoal(?string $goal): static { $this->goal = $goal; return $this; }

    public function getDietType(): ?string { return $this->dietType; }
    public function setDietType(?string $dietType): static { $this->dietType = $dietType; return $this; }

    /** @return Collection<int, DietMeal> */
    public function getDietMeals(): Collection { return $this->dietMeals; }

    public function addDietMeal(DietMeal $dietMeal): static
    {
        if (!$this->dietMeals->contains($dietMeal)) {
            $this->dietMeals->add($dietMeal);
            $dietMeal->setDiet($this);
        }
        return $this;
    }

    public function removeDietMeal(DietMeal $dietMeal): static
    {
        $this->dietMeals->removeElement($dietMeal);
        return $this;
    }

    /** @return Collection<int, User> */
    public function getUsers(): Collection { return $this->users; }
}
