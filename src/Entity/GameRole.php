<?php

namespace App\Entity;

use App\Repository\GameRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRoleRepository::class)]
class GameRole
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'gameRoles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    #[ORM\OneToMany(targetEntity: PlayerSession::class, mappedBy: 'gameRole', orphanRemoval: true)]
    private Collection $playerSessions;

    public function __construct()
    {
        $this->playerSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): static
    {
        $this->game = $game;

        return $this;
    }

    /**
     * @return Collection<int, PlayerSession>
     */
    public function getPlayerSessions(): Collection
    {
        return $this->playerSessions;
    }

    public function addPlayerSession(PlayerSession $playerSession): static
    {
        if (!$this->playerSessions->contains($playerSession)) {
            $this->playerSessions->add($playerSession);
            $playerSession->setGameRole($this);
        }

        return $this;
    }

    public function removePlayerSession(PlayerSession $playerSession): static
    {
        if ($this->playerSessions->removeElement($playerSession)) {
            // set the owning side to null (unless already changed)
            if ($playerSession->getGameRole() === $this) {
                $playerSession->setGameRole(null);
            }
        }

        return $this;
    }
}
