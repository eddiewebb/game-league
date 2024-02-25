<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(targetEntity: PlayerSession::class, mappedBy: 'session', orphanRemoval: true)]
    private Collection $playerSessions;

    #[ORM\ManyToOne(inversedBy: 'gameSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Game $game = null;

    public function __construct()
    {
        $this->playerSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

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
            $playerSession->setSession($this);
        }

        return $this;
    }

    public function removePlayerSession(PlayerSession $playerSession): static
    {
        if ($this->playerSessions->removeElement($playerSession)) {
            // set the owning side to null (unless already changed)
            if ($playerSession->getSession() === $this) {
                $playerSession->setSession(null);
            }
        }

        return $this;
    }



    /**
     * @return PlayerSession
     */
    public function getWinningPlayerSession()
    {   
        foreach ($this->playerSessions as $key => $playerSession) {
            if ($playerSession->isIsWinner()){
                return $playerSession;
            }
        }  
    }
}
