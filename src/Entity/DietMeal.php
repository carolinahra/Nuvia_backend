<?php

namespace App\Entity;

use App\Repository\DietMealRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DietMealRepository::class)]
#[ORM\Table(name: 'diet_meal')]
class DietMeal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Diet::class, inversedBy: 'dietMeals')]
    #[ORM\JoinColumn(name: 'diet_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Diet $diet;

    #[ORM\ManyToOne(targetEntity: Dish::class, inversedBy: 'dietMeals')]
    #[ORM\JoinColumn(name: 'dish_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Dish $dish;

    /** desayuno | media-mañana | comida | cena */
    #[ORM\Column(type: 'string', length: 255)]
    private string $mealType;

    /** 1 = lunes … 7 = domingo */
    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $dayOfWeek = null;

    public function getId(): ?int { return $this->id; }

    public function getDiet(): Diet { return $this->diet; }
    public function setDiet(Diet $diet): static { $this->diet = $diet; return $this; }

    public function getDish(): Dish { return $this->dish; }
    public function setDish(Dish $dish): static { $this->dish = $dish; return $this; }

    public function getMealType(): string { return $this->mealType; }
    public function setMealType(string $mealType): static { $this->mealType = $mealType; return $this; }

    public function getDayOfWeek(): ?int { return $this->dayOfWeek; }
    public function setDayOfWeek(?int $dayOfWeek): static { $this->dayOfWeek = $dayOfWeek; return $this; }
}
