<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: PlayerSession::class, mappedBy: 'player')]
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
