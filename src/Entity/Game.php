<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: GameRole::class, mappedBy: 'game', orphanRemoval: true)]
    private Collection $gameRoles;
   
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'game', orphanRemoval: true)]
    private Collection $sessions;

    public function __construct()
    {
        $this->gameRoles = new ArrayCollection();
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
     * @return Collection<int, GameRole>
     */
    public function getGameRoles(): Collection
    {
        return $this->gameRoles;
    }

    public function addGameRole(GameRole $gameRole): static
    {
        if (!$this->gameRoles->contains($gameRole)) {
            $this->gameRoles->add($gameRole);
            $gameRole->setGame($this);
        }

        return $this;
    }

    public function removeGameRole(GameRole $gameRole): static
    {
        if ($this->gameRoles->removeElement($gameRole)) {
            // set the owning side to null (unless already changed)
            if ($gameRole->getGame() === $this) {
                $gameRole->setGame(null);
            }
        }

        return $this;
    }




    /**
     * @return Collection<int, Session>
     */
    public function getsessions(): Collection
    {
        return $this->sessions;
    }

    public function addsession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setGame($this);
        }

        return $this;
    }

    public function removesession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getGame() === $this) {
                $session->setGame(null);
            }
        }

        return $this;
    }
}
