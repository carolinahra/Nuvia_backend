<?php

namespace App\Entity;

use App\Repository\UserMealLogRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserMealLogRepository::class)]
#[ORM\Table(name: 'user_meal_log')]
class UserMealLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'mealLogs')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: Dish::class, inversedBy: 'userMealLogs')]
    #[ORM\JoinColumn(name: 'dish_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Dish $dish;

    /** Número de raciones */
    #[ORM\Column(type: 'integer', options: ['unsigned' => true, 'default' => 1])]
    private int $quantity = 1;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function getId(): ?int { return $this->id; }

    public function getUser(): User { return $this->user; }
    public function setUser(User $user): static { $this->user = $user; return $this; }

    public function getDish(): Dish { return $this->dish; }
    public function setDish(Dish $dish): static { $this->dish = $dish; return $this; }

    public function getQuantity(): int { return $this->quantity; }
    public function setQuantity(int $quantity): static { $this->quantity = $quantity; return $this; }

    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): static { $this->createdAt = $createdAt; return $this; }
}
