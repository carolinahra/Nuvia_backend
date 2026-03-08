<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', options: ['unsigned' => true])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    private string $password;

    #[ORM\Column(type: 'integer', options: ['unsigned' => true], nullable: true)]
    private ?int $heightCm = null;

    #[ORM\Column(type: 'date', nullable: true)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $sex = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $activityLevel = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $goal = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isAdmin = false;

    #[ORM\ManyToOne(targetEntity: Diet::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'default_diet_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Diet $defaultDiet = null;

    #[ORM\ManyToOne(targetEntity: Routine::class, inversedBy: 'users')]
    #[ORM\JoinColumn(name: 'default_routine_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Routine $defaultRoutine = null;

    /** @var Collection<int, UserWeightLog> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserWeightLog::class)]
    private Collection $weightLogs;

    /** @var Collection<int, UserMealLog> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserMealLog::class)]
    private Collection $mealLogs;

    /** @var Collection<int, TrainingSession> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: TrainingSession::class)]
    private Collection $trainingSessions;

    /** @var Collection<int, Session> */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Session::class)]
    private Collection $sessions;

    public function __construct()
    {
        $this->weightLogs      = new ArrayCollection();
        $this->mealLogs        = new ArrayCollection();
        $this->trainingSessions = new ArrayCollection();
        $this->sessions        = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getUsername(): string { return $this->username; }
    public function setUsername(string $username): static { $this->username = $username; return $this; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }

    public function getPassword(): string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }

    public function getHeightCm(): ?int { return $this->heightCm; }
    public function setHeightCm(?int $heightCm): static { $this->heightCm = $heightCm; return $this; }

    public function getBirthdate(): ?\DateTimeInterface { return $this->birthdate; }
    public function setBirthdate(?\DateTimeInterface $birthdate): static { $this->birthdate = $birthdate; return $this; }

    public function getSex(): ?string { return $this->sex; }
    public function setSex(?string $sex): static { $this->sex = $sex; return $this; }

    public function getActivityLevel(): ?string { return $this->activityLevel; }
    public function setActivityLevel(?string $activityLevel): static { $this->activityLevel = $activityLevel; return $this; }

    public function getGoal(): ?string { return $this->goal; }
    public function setGoal(?string $goal): static { $this->goal = $goal; return $this; }

    public function isAdmin(): bool { return $this->isAdmin; }
    public function setIsAdmin(bool $isAdmin): static { $this->isAdmin = $isAdmin; return $this; }

    public function getDefaultDiet(): ?Diet { return $this->defaultDiet; }
    public function setDefaultDiet(?Diet $defaultDiet): static { $this->defaultDiet = $defaultDiet; return $this; }

    public function getDefaultRoutine(): ?Routine { return $this->defaultRoutine; }
    public function setDefaultRoutine(?Routine $defaultRoutine): static { $this->defaultRoutine = $defaultRoutine; return $this; }

    /** @return Collection<int, UserWeightLog> */
    public function getWeightLogs(): Collection { return $this->weightLogs; }

    public function addWeightLog(UserWeightLog $log): static
    {
        if (!$this->weightLogs->contains($log)) {
            $this->weightLogs->add($log);
            $log->setUser($this);
        }
        return $this;
    }

    public function removeWeightLog(UserWeightLog $log): static
    {
        $this->weightLogs->removeElement($log);
        return $this;
    }

    /** @return Collection<int, UserMealLog> */
    public function getMealLogs(): Collection { return $this->mealLogs; }

    public function addMealLog(UserMealLog $log): static
    {
        if (!$this->mealLogs->contains($log)) {
            $this->mealLogs->add($log);
            $log->setUser($this);
        }
        return $this;
    }

    public function removeMealLog(UserMealLog $log): static
    {
        $this->mealLogs->removeElement($log);
        return $this;
    }

    /** @return Collection<int, TrainingSession> */
    public function getTrainingSessions(): Collection { return $this->trainingSessions; }

    public function addTrainingSession(TrainingSession $ts): static
    {
        if (!$this->trainingSessions->contains($ts)) {
            $this->trainingSessions->add($ts);
            $ts->setUser($this);
        }
        return $this;
    }

    public function removeTrainingSession(TrainingSession $ts): static
    {
        $this->trainingSessions->removeElement($ts);
        return $this;
    }

    /** @return Collection<int, Session> */
    public function getSessions(): Collection { return $this->sessions; }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setUser($this);
        }
        return $this;
    }

    public function removeSession(Session $session): static
    {
        $this->sessions->removeElement($session);
        return $this;
    }
}
