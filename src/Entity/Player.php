<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_HANDLE', fields: ['handle'])]
class Player implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $handle = null;

    #[ORM\Column(length: 180)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: PlayerSession::class, mappedBy: 'player')]
    private Collection $playerSessions;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHandle(): ?string
    {
        return $this->handle;
    }

    public function setHandle(string $handle): static
    {
        $this->handle = $handle;

        return $this;
    }
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }


    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->handle;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    /**
     * @return Collection<int, PlayerSession>
     */
    public function getPlayerSessions(): Collection
    {
        return $this->playerSessions;
    }
        /**
     * @return Collection<int, PlayerSession>
     */
    public function getWinningPlayerSessions(): Collection
    {
        return $this->playerSessions->filter( 
            fn($value) => $value->isIsWinner()
        );
    }

    public function addPlayerSession(PlayerSession $playerSession): static
    {
        if (!$this->playerSessions->contains($playerSession)) {
            $this->playerSessions->add($playerSession);
            $playerSession->setPlayer($this);
        }

        return $this;
    }

    public function removePlayerSession(PlayerSession $playerSession): static
    {
        if ($this->playerSessions->removeElement($playerSession)) {
            // set the owning side to null (unless already changed)
            if ($playerSession->getPlayer() === $this) {
                $playerSession->setPlayer(null);
            }
        }

        return $this;
    }
}
