<?php

namespace App\Entity;

use App\Repository\PlayerSessionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerSessionRepository::class)]
class PlayerSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'playerSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Session $session = null;

    #[ORM\Column(nullable: true)]
    private ?int $score = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isWinner = null;

    #[ORM\ManyToOne(inversedBy: 'playerSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GameRole $gameRole = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): static
    {
        $this->session = $session;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): static
    {
        $this->score = $score;

        return $this;
    }

    public function isIsWinner(): ?bool
    {
        return $this->isWinner;
    }

    public function setIsWinner(?bool $isWinner): static
    {
        $this->isWinner = $isWinner;

        return $this;
    }

    public function getGameRole(): ?GameRole
    {
        return $this->gameRole;
    }

    public function setGameRole(?GameRole $gameRole): static
    {
        $this->gameRole = $gameRole;

        return $this;
    }
}
