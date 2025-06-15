<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Enum\StatusUserEnum;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\Index(name: 'IDX_USER_EMAIL', columns: ['email'])]
#[ORM\Index(name: 'IDX_USER_FULL_NAME', columns: ['full_name'])]
#[ORM\Index(name: 'IDX_USER_TEL', columns: ['tel'])]
#[ORM\Index(name: 'IDX_USER_CREATED_AT', columns: ['created_at'])]
#[ORM\Index(name: 'IDX_USER_UPDATED_AT', columns: ['updated_at'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface {

    use AbstractEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[NotBlank()]
    #[Assert\Email()]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(min: 5, max: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[NotBlank()]
    #[Assert\Length(min: 5, max: 255)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cv = null;

    #[ORM\Column(type: "status_user_enum", nullable: false)]
    private ?string $status = StatusUserEnum::USER_IN_INTER_CONTRACT->name;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'users')]
    private ?self $manager = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'manager')]
    private Collection $users;

    /**
     * @var Collection<int, Evaluation>
     */
    #[ORM\OneToMany(targetEntity: Evaluation::class, mappedBy: 'user')]
    private Collection $evaluations;

    /**
     * @var Collection<int, Mission>
     */
    #[ORM\OneToMany(targetEntity: Mission::class, mappedBy: 'user')]
    private Collection $missions;

    /**
     * @var Collection<int, Skill>
     */
    #[ORM\OneToMany(targetEntity: Skill::class, mappedBy: 'user')]
    private Collection $skills;

    /**
     * @var Collection<int, Leave>
     */
    #[ORM\OneToMany(targetEntity: Leave::class, mappedBy: 'user')]
    private Collection $leaves;

    public function __construct() {
        $this->users = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->leaves = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getEmail(): ?string {
        return $this->email;
    }

    public function setEmail(string $email): static {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @return string The email
     */
    public function getUserIdentifier(): string {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return array<string>
     */
    public function getRoles(): array {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string {
        return $this->password;
    }

    public function setPassword(string $password): static {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getTel(): ?string {
        return $this->tel;
    }

    public function setTel(?string $tel): static {
        $this->tel = $tel;

        return $this;
    }

    public function getFullName(): ?string {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): static {
        $this->fullName = $fullName;

        return $this;
    }

    public function __toString(): string {
        return $this->fullName;
    }

    public function getAdresse(): ?string {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCv(): ?string {
        return $this->cv;
    }

    public function setCv(?string $cv): static {
        $this->cv = $cv;

        return $this;
    }

    public function getStatus(): ?string {
        return $this->status;
    }

    public function setStatus(string $status): static {
        $this->status = $status;

        return $this;
    }

    public function getManager(): ?self {
        return $this->manager;
    }

    public function setManager(?self $manager): static {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getUsers(): Collection {
        return $this->users;
    }

    public function addUser(self $user): static {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setManager($this);
        }

        return $this;
    }

    public function removeUser(self $user): static {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getManager() === $this) {
                $user->setManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Evaluation>
     */
    public function getEvaluations(): Collection {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): static {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations->add($evaluation);
            $evaluation->setUser($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): static {
        if ($this->evaluations->removeElement($evaluation)) {
            // set the owning side to null (unless already changed)
            if ($evaluation->getUser() === $this) {
                $evaluation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection {
        return $this->missions;
    }

    public function addMission(Mission $mission): static {
        if (!$this->missions->contains($mission)) {
            $this->missions->add($mission);
            $mission->setUser($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): static {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getUser() === $this) {
                $mission->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Skill>
     */
    public function getSkills(): Collection {
        return $this->skills;
    }

    public function addSkill(Skill $skill): static {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
            $skill->setUser($this);
        }

        return $this;
    }

    public function removeSkill(Skill $skill): static {
        if ($this->skills->removeElement($skill)) {
            // set the owning side to null (unless already changed)
            if ($skill->getUser() === $this) {
                $skill->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Leave>
     */
    public function getLeaves(): Collection {
        return $this->leaves;
    }

    public function addLeaf(Leave $leaf): static {
        if (!$this->leaves->contains($leaf)) {
            $this->leaves->add($leaf);
            $leaf->setUser($this);
        }

        return $this;
    }

    public function removeLeaf(Leave $leaf): static {
        if ($this->leaves->removeElement($leaf)) {
            // set the owning side to null (unless already changed)
            if ($leaf->getUser() === $this) {
                $leaf->setUser(null);
            }
        }

        return $this;
    }
}
